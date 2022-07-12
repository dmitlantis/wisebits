<?php
namespace crud;

class ActionInvoker
{
    protected IAction $action;

    public function __construct(protected \persistence\IPersister $persister)
    {
    }

    /**
     * @param IAction $action
     */
    public function setAction(IAction $action): void
    {
        $this->action = $action;
    }

    public function execute(): mixed
    {
        return $this->action->execute($this->persister);
    }

}