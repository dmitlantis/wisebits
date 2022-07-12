<?php
namespace persistence;

interface IPersistable
{
    public function getPersistanceStorageName():string;
    public function getPersistanceIdentifierKey():string;
    public function getPersistanceIdentifierValue():?int;
    public function setPersistanceIdentifierValue(int $value);
    public function getPersistableRepresentation():array;
    public function fillFromPersistableRepresentation(array $data);
}