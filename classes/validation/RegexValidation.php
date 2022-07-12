<?php
namespace validation;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class RegexValidation implements IFormatValidation
{
    public function __construct(protected string $regex)
    {
    }

    public function validate($value)
    {
        if (!preg_match($this->regex, $value)) {
            throw new ValidationException("Value `$value` does not match regex `$this->regex`");
        }
    }
}