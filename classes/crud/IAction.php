<?php
namespace crud;

interface IAction
{
    public function execute(\persistence\IPersister $persister);
}