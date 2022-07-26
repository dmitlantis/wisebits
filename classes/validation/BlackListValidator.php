<?php


namespace validation;


class BlackListValidator extends AbstractValidator
{

    public function __construct(protected \persistence\IBlackListProvider $blackListProvider){

    }

    protected function propertyValidation(IValidatable $entity, string $propertyName, IValidation $validation)
    {
        foreach ($this->blackListProvider->provideBlackList() as $badWord) {
            if (str_contains($entity->$propertyName, $badWord)) {
                throw new ValidationException($entity::class . " property $propertyName blacklist validation error!");
            }
        }
    }

    protected function getValidationClass(): string
    {
        return BlackListValidation::class;
    }
}