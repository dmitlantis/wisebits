<?php
namespace crud;

class UserUpdateAction implements IAction
{

    public function __construct(protected int $id, protected ?string $name = null, protected ?string $email = null)
    {
    }

    /**
     * @throws \persistence\PersistanceException
     * @throws \validation\ValidationException
     * @throws \ReflectionException
     */
    public function execute(\persistence\IPersister $persister)
    {
        $user = new \entities\User();
        $user->id = $this->id;
        $persister->get($user);
        $user->name ??= $this->name;
        $user->email ??= $this->email;

        (new \validation\FormatValidator($user))->validate();
        (new \validation\UniqueValidator($user, $persister))->validate();

        $persister->persist($user);
    }
}