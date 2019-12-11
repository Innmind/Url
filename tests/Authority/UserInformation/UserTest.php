<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\{
    Authority\UserInformation\User,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInterface()
    {
        $u = User::of('foo');

        $this->assertInstanceOf(User::class, $u);
        $this->assertSame('foo', $u->toString());
    }

    public function testThrowWhenInvalidUser()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('user@me');

        User::of('user@me');
    }

    public function testNull()
    {
        $this->assertInstanceOf(User::class, User::none());
        $this->assertSame('', User::none()->toString());
    }
}
