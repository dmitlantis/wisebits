<?php
namespace db;

class DbStub implements IDB
{


    public function insert(array $data, string $table):int
    {
        // TODO: Implement insert() method.
    }

    public function update(array $data, string $table, array $where)
    {
        // TODO: Implement update() method.
    }

    public function select(string $table, array $where):?array
    {
        // TODO: Implement select() method.
    }
}