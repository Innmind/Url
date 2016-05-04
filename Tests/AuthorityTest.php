<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Authority,
    AuthorityInterface,
    Authority\UserInformation,
    Authority\UserInformation\NullUser,
    Authority\UserInformation\NullPassword,
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
    Authority\NullHost,
    Authority\NullPort,
    Authority\Host,
    Authority\Port
};

class AuthorityTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $a = new Authority(
            $u = new UserInformation(
                new NullUser,
                new NullPassword
            ),
            $h = new NullHost,
            $p = new NullPort
        );

        $this->assertInstanceOf(AuthorityInterface::class, $a);
        $this->assertSame($u, $a->userInformation());
        $this->assertSame($h, $a->host());
        $this->assertSame($p, $a->port());
        $this->assertSame('', (string) $a);

        $a = new Authority(
            new UserInformation(
                new User('foo'),
                new Password('bar')
            ),
            new Host('localhost'),
            new Port(8080)
        );

        $this->assertSame('foo:bar@localhost:8080', (string) $a);

        $a = new Authority(
            new UserInformation(
                new NullUser,
                new NullPassword
            ),
            new Host('localhost'),
            new NullPort
        );

        $this->assertSame('localhost', (string) $a);
    }
}
