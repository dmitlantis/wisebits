<?php
namespace validation;

interface IFormatValidation extends IValidation
{
    /**
     * @throws ValidationException
     */
    public function validate($value);
}