<?php


namespace validation;


class UniqueValidator extends AbstractValidator
{

    public function __construct(IValidatable $entity, protected \persistence\IPersister $persister)
    {
        parent::__construct($entity);
    }

    protected function propertyValidation(string $propertyName, IValidation $validation)
    {
        $existed = clone $this->entity;
        try {
            $this->persister->getByProperty($propertyName, $existed);
        } catch (\persistence\PersistanceException) {
            return; // больше такого объекта нет, уникальность обеспечена
        }

        if ($existed->id != $this->entity->id) { // нашли не тот же объект, что есть
            throw new ValidationException($this->entity::class . " with such $propertyName already exists!");
        }
    }

    protected function getValidationClass(): string
    {
        return IUniqueValidation::class;
    }
}