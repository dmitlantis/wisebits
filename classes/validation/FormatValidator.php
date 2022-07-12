<?php


namespace validation;


class FormatValidator extends AbstractValidator
{

    /**
     * @param string            $propertyName
     * @param IFormatValidation $validation
     * @throws ValidationException
     */
    protected function propertyValidation(string $propertyName, IValidation $validation)
    {
        $validation->validate($this->entity->$propertyName);
    }

    protected function getValidationClass(): string
    {
        return IFormatValidation::class;
    }
}