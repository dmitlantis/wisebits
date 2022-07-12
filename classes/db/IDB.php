<?php
namespace db;

interface IDB
{
    public function insert(array $data, string $table):int;
    public function update(array $data, string $table, array $where);
    public function select(string $table, array $where):?array;
}