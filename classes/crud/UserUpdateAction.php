<?php
namespace crud;

class UserUpdateAction implements IAction
{

    public function __construct(protected int $id, protected \validation\IValidator $validator, protected ?string $name = null, protected ?string $email = null)
    {
    }

    /**
     * @param \persistence\IPersister $persister
     * @throws \ReflectionException
     * @throws \persistence\PersistanceException
     * @throws \validation\ValidationException
     */
    public function execute(\persistence\IPersister $persister)
    {
        $user = new \entities\User();
        $user->id = $this->id;
        $persister->get($user);
        $user->name = $this->name ?? $user->name;
        $user->email = $this->email ?? $user->email;

        $this->validator->validate($user);

        $persister->persist($user);
    }
}