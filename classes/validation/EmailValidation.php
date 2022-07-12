<?php


namespace validation;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class EmailValidation implements IFormatValidation
{

    public function validate($value)
    {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new ValidationException("Value `$value` is not correct email");
        }
    }
}