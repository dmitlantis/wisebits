<?php


namespace persistence;


interface IBlackListProvider
{
    /** @return string[] */
    public function provideBlackList():array;
}