<?php


class FormatValidationTest extends \PHPUnit\Framework\TestCase
{
    protected \entities\User $user;
    protected \validation\FormatValidator $validator;

    public function setUp(): void
    {
        $this->user = new \entities\User();
        $this->validator = new \validation\FormatValidator();
    }

    public function testValidUser()
    {
        $this->expectNotToPerformAssertions();
        $this->user->email = 'asdf@asdf.com';
        $this->user->name = 'zaksnyder01';
        $this->validator->validate($this->user);
    }

    public function testInvalidEmail()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->user->email = '@asdf.com';
        $this->user->name = 'zaksnyder01';
        $this->validator->validate($this->user);
    }

    public function testInvalidUsername()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->user->email = 'asdf@asdf.com';
        $this->user->name = 'zak-snyder01';
        $this->validator->validate($this->user);
    }

    public function testInvalidShortUsername()
    {
        $this->expectException(\validation\ValidationException::class);
        $this->user->email = 'asdf@asdf.com';
        $this->user->name = 'zak';
        $this->validator->validate($this->user);
    }

}