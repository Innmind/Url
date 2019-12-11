<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInterface()
    {
        $u = User::of('foo');

        $this->assertInstanceOf(User::class, $u);
        $this->assertSame('foo', (string) $u);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidUser()
    {
        User::of('user@me');
    }

    public function testNull()
    {
        $this->assertInstanceOf(User::class, User::null());
        $this->assertSame('', (string) User::null());
    }
}
