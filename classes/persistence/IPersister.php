<?php
namespace persistence;

interface IPersister
{
    public function persist(IPersistable $persistable);
    /**
     * @param IPersistable $persistable
     * @throws PersistanceException
     */
    public function get(IPersistable $persistable);
    /**
     * @throws PersistanceException
     */
    public function getByProperty(string $property, IPersistable $persistable);

}