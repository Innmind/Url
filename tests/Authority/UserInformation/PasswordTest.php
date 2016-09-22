<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\{
    Password,
    PasswordInterface
};

class PasswordTest extends \PHPUnit_Framework_TestCase
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
