<?php


namespace validation;


class FormatValidator extends AbstractValidator
{

    /**
     * @param IValidatable      $entity
     * @param string            $propertyName
     * @param IFormatValidation $validation
     * @throws ValidationException
     */
    protected function propertyValidation(IValidatable $entity, string $propertyName, IValidation $validation)
    {
        $validation->validate($entity->$propertyName);
    }

    protected function getValidationClass(): string
    {
        return IFormatValidation::class;
    }
}