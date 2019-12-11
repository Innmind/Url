<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\{
    User,
    UserInterface
};
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testInterface()
    {
        $u = User::of('foo');

        $this->assertInstanceOf(UserInterface::class, $u);
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
        $this->assertInstanceOf(UserInterface::class, User::null());
        $this->assertSame('', (string) User::null());
    }
}
