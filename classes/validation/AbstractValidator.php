<?php
namespace validation;

abstract class AbstractValidator implements IValidator
{

    /**
     * @throws \ReflectionException
     * @throws ValidationException
     */
    public function validate(IValidatable $entity)
    {
        $reflection = new \ReflectionClass($entity::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes($this->getValidationClass(), \ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attribute) {
                /** @var IValidation $validation */
                $validation = $attribute->newInstance();
                $this->propertyValidation($entity, $propertyName, $validation);
            }
        }
    }

    /**
     * @param IValidatable $entity
     * @param string       $propertyName
     * @param IValidation  $validation
     * @throws ValidationException
     */
    abstract protected function propertyValidation(IValidatable $entity, string $propertyName, IValidation $validation);

    abstract protected function getValidationClass():string;

}