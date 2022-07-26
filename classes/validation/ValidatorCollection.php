<?php


namespace validation;


class ValidatorCollection implements IValidator
{
    /** @var IValidator[] $validators */
    protected array $validators;

    /**
     * ValidatorCollection constructor.
     */
    public function __construct(IValidator ...$validators){
        $this->validators = $validators;
    }

    /**
     * @param IValidatable $entity
     * @throws ValidationException
     * @throws \ReflectionException
     */
    public function validate(IValidatable $entity)
    {
        foreach ($this->validators as $validator) {
            $validator->validate($entity);
        }
    }
}