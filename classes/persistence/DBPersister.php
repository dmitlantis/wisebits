<?php

namespace persistence;

class DBPersister implements IPersister
{
    protected $persistancePool;

    public function __construct(protected \db\IDB $db, protected \log\ILogger $log)
    {
    }


    public function persist(IPersistable $persistable)
    {
        if (is_null($persistable->getPersistanceIdentifierValue())) {
            $id = $this->db->insert($persistable->getPersistableRepresentation(), $persistable->getPersistanceStorageName());
            $persistable->setPersistanceIdentifierValue($id);
        } else {
            $this->logUpdates($persistable);
            $this->db->update($persistable->getPersistableRepresentation(), $persistable->getPersistanceStorageName(), [$persistable->getPersistanceIdentifierKey() => $persistable->getPersistanceIdentifierValue()]);
        }
        $this->storeInPool($persistable);
    }

    protected function logUpdates(IPersistable $persistable)
    {
        if ($poolRepresentation = $this->getFromPool($persistable)) {
            $updatedRepresentation = $persistable->getPersistableRepresentation();
            foreach ($updatedRepresentation as $property => $value) {
                $oldValue = $poolRepresentation[$property] ?? null;
                if ($value !== $oldValue) {
                    $this->log->log("Property `$property` changed from `$oldValue` to `$value`");
                }
            }
        }
    }

    /**
     * @param IPersistable $persistable
     * @throws PersistanceException
     */
    public function get(IPersistable $persistable)
    {
        $data = $this->db->select($persistable->getPersistanceStorageName(), [$persistable->getPersistanceIdentifierKey() => $persistable->getPersistanceIdentifierValue()]);
        if (!$data) {
            throw new PersistanceException($persistable::class . " with id #" . $persistable->getPersistanceIdentifierValue() . 'not found');
        }
        $persistable->fillFromPersistableRepresentation($data);
        $this->storeInPool($persistable);
    }

    /**
     * @throws PersistanceException
     */
    public function getByProperty(string $property, IPersistable $persistable)
    {
        $data = $this->db->select($persistable->getPersistanceStorageName(), [$property => $persistable->$property]);
        if (!$data) {
            throw new PersistanceException($persistable::class . " with id #" . $persistable->getPersistanceIdentifierValue() . 'not found');
        }
        $persistable->fillFromPersistableRepresentation($data);
        $this->storeInPool($persistable);
    }

    public function storeInPool(IPersistable $persistable) {
        $this->persistancePool[$persistable->getPersistanceStorageName()][$persistable->getPersistanceIdentifierValue()] = $persistable->getPersistableRepresentation();
    }

    public function getFromPool(IPersistable $persistable) :?array {
        return $this->persistancePool[$persistable->getPersistanceStorageName()][$persistable->getPersistanceIdentifierValue()] ?? null;
    }
}