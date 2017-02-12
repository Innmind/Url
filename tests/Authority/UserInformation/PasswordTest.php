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
        $p = new Password('foo');

        $this->assertInstanceOf(PasswordInterface::class, $p);
        $this->assertSame('foo', (string) $p);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidPassword()
    {
        new Password('foo@bar');
    }
}
