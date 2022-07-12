<?php


use JetBrains\PhpStorm\ArrayShape;

class CrudTest extends \PHPUnit\Framework\TestCase
{
    protected \crud\ActionInvoker $invoker;
    protected \db\DbStub $dbStub;
    protected \log\CumulativeLogger $logger;

    public function setUp(): void
    {
        $this->dbStub = $this->createMock(\db\DbStub::class);
        $this->logger = new \log\CumulativeLogger();
        $this->invoker = new \crud\ActionInvoker(new \persistence\DBPersister($this->dbStub, $this->logger));
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
        $this->invoker->setAction(new \crud\UserCreateAction('username', 'test@mail.com'));
        $this->dbStub
            ->expects($this->once())
            ->method('insert')
            ->willReturn(1);
        $this->invoker->execute();
    }

    public function testCreateUniqueFail()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->invoker->setAction(new \crud\UserCreateAction('username', 'test@mail.com'));
        $data = [
            ['users', ['name'=>'username'], ['id' => 1]],
            ['users', ['email'=>'test@mail.com'], ['id' => 1]],
        ];
        $this->dbStub
            ->method('select')
            ->willReturnMap($data);
        $this->invoker->execute();
    }

    public function testUpdateSuccess()
    {
        $this->invoker->setAction(new \crud\UserUpdateAction(1, email: 'crudine@lavista.com'));
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
        $this->invoker->setAction(new \crud\UserUpdateAction(1, email: 'crudine@lavista.com'));
        $this->dbStub
            ->method('select')
            ->willReturn(null);
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
}