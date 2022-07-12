<?php
namespace crud;

class UserReadAction implements IAction
{
    public function __construct(protected int $id){

    }

    public function execute(\persistence\IPersister $persister)
    {
        $user = new \entities\User();
        $user->id = $this->id;
        try {
            $persister->get($user);
        } catch (\persistence\PersistanceException) {
            return null;
        }
        return [
            'name' => $user->name,
            'email' => $user->email,
        ];
    }
}