<?php


namespace persistence;


class BlacklistStub implements IBlackListProvider
{

    public function provideBlackList(): array
    {
        return [
            'badword'
        ];
    }
}