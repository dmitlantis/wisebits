<?php


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class CrudTest extends \PHPUnit\Framework\TestCase
{
    protected \crud\ActionInvoker   $invoker;
    protected \db\DbStub            $dbStub;
    protected \log\CumulativeLogger $logger;
    protected \persistence\IPersister $persister;

    public function setUp(): void
    {
        $this->dbStub    = $this->createMock(\db\DbStub::class);
        $this->logger    = new \log\CumulativeLogger();
        $this->persister = new \persistence\DBPersister($this->dbStub, $this->logger);
        $this->invoker   = new \crud\ActionInvoker($this->persister);
    }

    public function testReadSuccess()
    {
        $this->invoker->setAction(new \crud\UserReadAction(1));
        $data = $this->provideUserRepresentation();
        $this->dbStub->method('select')->willReturn($data);
        $this->assertIsArray($this->invoker->execute());
    }

    public function testReadNotFound()
    {
        $this->invoker->setAction(new \crud\UserReadAction(1));
        $this->dbStub->method('select')->willReturn(null);
        $this->assertNull($this->invoker->execute());
    }

    public function testCreateSuccess()
    {
        $this->invoker->setAction(new \crud\UserCreateAction( 'username', 'test@mail.com', $this->provideUserValidators()));
        $this->dbStub
            ->expects($this->once())
            ->method('insert')
            ->willReturn(1);
        $this->invoker->execute();
    }

    public function testCreateUniqueFail()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->invoker->setAction(new \crud\UserCreateAction('username', 'test@mail.com', $this->provideUserValidators()));
        $data = [
            ['users', ['name'=>'username'], ['id' => 1]],
            ['users', ['email'=>'test@mail.com'], ['id' => 1]],
        ];
        $this->dbStub
            ->method('select')
            ->willReturnMap($data);
        $this->invoker->execute();
    }

    public function testCreateInvalidEmailFail()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->invoker->setAction(new \crud\UserCreateAction('username', '@mail.com', $this->provideUserValidators()));
        $this->invoker->execute();
    }

    public function testCreateBlackListNameFail()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->invoker->setAction(new \crud\UserCreateAction('badword', 'test@mail.com', $this->provideUserValidators()));
        $this->invoker->execute();
    }

    public function testUpdateSuccess()
    {
        $this->invoker->setAction(new \crud\UserUpdateAction(1, $this->provideUserValidators(), email: 'crudine@lavista.com'));
        $this->dbStub
            ->expects($this->once())
            ->method('update');
        $this->dbStub
            ->method('select')
            ->willReturn($this->provideUserRepresentation());
        $this->invoker->execute();
    }

    public function testUpdateFail()
    {
        $this->expectException(\persistence\PersistanceException::class);
        $this->invoker->setAction(new \crud\UserUpdateAction(1, $this->provideUserValidators(), email: 'crudine@lavista.com'));
        $this->dbStub
            ->method('select')
            ->willReturn(null);
        $this->invoker->execute();
    }

    public function testUpdateInvalidNameFail()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->invoker->setAction(new \crud\UserUpdateAction(1, $this->provideUserValidators(), name: ''));
        $this->dbStub
            ->method('select')
            ->willReturn($this->provideUserRepresentation());
        $this->invoker->execute();
    }

    public function testDeleteSuccess()
    {
        $this->invoker->setAction(new \crud\UserDeleteAction(1));
        $this->dbStub
            ->method('select')
            ->willReturn($this->provideUserRepresentation());
        $this->dbStub
            ->expects($this->once())
            ->method('update')
            ->with($this->arrayHasKey('deleted'));
        $this->invoker->execute();
    }

    public function testDeleteFail()
    {
        $this->expectException(LogicException::class);
        $this->invoker->setAction(new \crud\UserDeleteAction(1));
        $this->dbStub
            ->method('select')
            ->willReturn(['deleted' => time()] + $this->provideUserRepresentation());
        $this->invoker->execute();
    }

    /**
     * @return array
     */
    #[ArrayShape(['id' => "int", 'name' => "string", 'email' => "string", 'created' => "int", 'deleted' => "null|int"])]
    public function provideUserRepresentation(): array
    {
        return ['id' => 1, 'name' => 'username', 'email' => 'test@mail.com', 'created' => time(), 'deleted' => null];
    }

    #[Pure] public function provideUserValidators():\validation\ValidatorCollection
    {
        $userValidators = [
            new \validation\FormatValidator(),
            new \validation\UniqueValidator($this->persister),
            new \validation\BlackListValidator(new \persistence\BlacklistStub())
        ];
        return new \validation\ValidatorCollection(...$userValidators);
    }
}