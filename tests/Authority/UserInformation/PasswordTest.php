<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\Password;
use PHPUnit\Framework\TestCase;

class PasswordTest extends TestCase
{
    public function testInterface()
    {
        $p = Password::of('foo');

        $this->assertInstanceOf(Password::class, $p);
        $this->assertSame('foo', $p->toString());
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
        $this->assertInstanceOf(Password::class, Password::none());
        $this->assertSame('', Password::none()->toString());
    }
}
