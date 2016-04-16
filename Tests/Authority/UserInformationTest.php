<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    UserInformation,
    UserInformationInterface,
    UserInformation\User,
    UserInformation\NullUser,
    UserInformation\Password,
    UserInformation\NullPassword
};

class UserInformationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $ui = new UserInformation(
            $u = new User('foo'),
            $p = new Password('bar')
        );

        $this->assertInstanceOf(UserInformationInterface::class, $ui);
        $this->assertSame($u, $ui->user());
        $this->assertSame($p, $ui->password());
        $this->assertSame('foo:bar', (string) $ui);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidUserInformationException
     */
    public function testThrowWhenNullUserButPasswordPresent()
    {
        new UserInformation(new NullUser, new Password('foo'));
    }

    public function testString()
    {
        $this->assertSame(
            'foo',
            (string )new UserInformation(new User('foo'), new NullPassword)
        );
        $this->assertSame(
            '',
            (string) new UserInformation(new NullUser, new NullPassword)
        );
    }
}
