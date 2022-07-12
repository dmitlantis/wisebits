<?php
namespace crud;

class UserCreateAction implements IAction
{

    public function __construct(protected string $name, protected string $email)
    {
    }

    /**
     * @throws \ReflectionException
     * @throws \validation\ValidationException
     */
    public function execute(\persistence\IPersister $persister)
    {
        $user = new \entities\User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->created = time();

        (new \validation\FormatValidator($user))->validate();
        (new \validation\UniqueValidator($user, $persister))->validate();

        $persister->persist($user);
    }
}