<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\{
    Password,
    PasswordInterface
};
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testInterface()
    {
        $p = Password::of('foo');

        $this->assertInstanceOf(PasswordInterface::class, $p);
        $this->assertSame('foo', (string) $p);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidPassword()
    {
        Password::of('foo@bar');
    }

    public function testNull()
    {
        $this->assertInstanceOf(PasswordInterface::class, Password::null());
        $this->assertSame('', (string) Password::null());
    }
}
