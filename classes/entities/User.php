<?php
namespace entities;

class User implements \validation\IValidatable, \persistence\IPersistable {

    public ?int $id = null;
    #[\validation\RegexValidation('/^[a-z0-9]{8,}$/')]
    #[\validation\UniqueValidation]
    #[\validation\BlackListValidation]
    public string $name;
    #[\validation\EmailValidation]
    #[\validation\UniqueValidation]
    public string $email;
    public int $created;
    public ?int $deleted = null;

    public function __construct()
    {
    }

    public function getPersistableRepresentation(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created' => $this->created,
            'deleted' => $this->deleted,
        ];
    }

    public function getPersistanceIdentifierKey(): string
    {
        return 'id';
    }

    public function getPersistanceIdentifierValue():? int
    {
        return $this->id;
    }

    public function getPersistanceStorageName(): string
    {
        return 'users';
    }

    public function setPersistanceIdentifierValue(int $value)
    {
        $this->id = $value;
    }

    public function fillFromPersistableRepresentation(array $data)
    {
        foreach ($data as $property => $value) {
            if (property_exists($this,$property)) {
                $this->$property = $value;
            }
        }
    }
}