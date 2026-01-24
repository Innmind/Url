<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\User;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInterface()
    {
        $u = User::of('foo');

        $this->assertInstanceOf(User::class, $u);
        $this->assertSame('foo', $u->toString());
        $this->assertSame('user%40me', User::of('user@me')->toString());
    }

    public function testNull()
    {
        $this->assertInstanceOf(User::class, User::none());
        $this->assertSame('', User::none()->toString());
    }

    public function testEquals()
    {
        $this->assertTrue(User::none()->equals(User::none()));
        $this->assertTrue(User::of('foo')->equals(User::of('foo')));
        $this->assertFalse(User::of('bar')->equals(User::of('foo')));
    }
}
