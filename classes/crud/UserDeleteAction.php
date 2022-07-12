<?php
namespace crud;

class UserDeleteAction implements IAction
{

    public function __construct(protected int $id){

    }

    /**
     * @throws \persistence\PersistanceException
     * @throws \LogicException
     */
    public function execute(\persistence\IPersister $persister)
    {
        $user = new \entities\User();
        $user->id = $this->id;
        $persister->get($user);
        if ($user->deleted) {
            throw new \LogicException('Already deleted');
        }
        $user->deleted = time();
        $persister->persist($user);
    }
}