<?php
namespace validation;

abstract class AbstractValidator
{

    public function __construct(protected IValidatable $entity)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws ValidationException
     */
    public function validate()
    {
        $reflection = new \ReflectionClass($this->entity::class);
        $properties = $reflection->getProperties();

        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes($this->getValidationClass(), \ReflectionAttribute::IS_INSTANCEOF);
            foreach ($attributes as $attribute) {
                /** @var IValidation $validation */
                $validation = $attribute->newInstance();
                $this->propertyValidation($propertyName, $validation);
            }
        }
    }

    /**
     * @param string      $propertyName
     * @param IValidation $validation
     * @throws ValidationException
     */
    abstract protected function propertyValidation(string $propertyName, IValidation $validation);

    abstract protected function getValidationClass():string;

}