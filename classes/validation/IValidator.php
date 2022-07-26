<?php


namespace validation;


interface IValidator
{
    /**
     * @param IValidatable $entity
     * @throws ValidationException
     * @throws \ReflectionException
     */
    public function validate(IValidatable $entity);
}