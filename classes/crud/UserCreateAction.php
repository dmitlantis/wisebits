<?php
namespace crud;

class UserCreateAction implements IAction
{

    public function __construct(protected string $name, protected string $email, protected \validation\IValidator $validator)
    {
    }

    /**
     * @param \persistence\IPersister $persister
     * @throws \ReflectionException
     * @throws \validation\ValidationException
     */
    public function execute(\persistence\IPersister $persister)
    {
        $user = new \entities\User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->created = time();

        $this->validator->validate($user);

        $persister->persist($user);
    }
}