<?php


namespace validation;


class UniqueValidator extends AbstractValidator
{

    public function __construct(protected \persistence\IPersister $persister)
    {
    }

    protected function propertyValidation(IValidatable $entity, string $propertyName, IValidation $validation)
    {
        $existed = clone $entity;
        try {
            $this->persister->getByProperty($propertyName, $existed);
        } catch (\persistence\PersistanceException) {
            return; // больше такого объекта нет, уникальность обеспечена
        }

        if ($existed->id != $entity->id) { // нашли не тот же объект, что есть
            throw new ValidationException($entity::class . " with such $propertyName already exists!");
        }
    }

    protected function getValidationClass(): string
    {
        return IUniqueValidation::class;
    }
}