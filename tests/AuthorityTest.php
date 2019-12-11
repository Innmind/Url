<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Authority,
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
    Authority\Host,
    Authority\Port,
};
use PHPUnit\Framework\TestCase;

class AuthorityTest extends TestCase
{
    public function testInterface()
    {
        $a = Authority::of(
            $u = UserInformation::of(
                User::null(),
                Password::null()
            ),
            $h = Host::null(),
            $p = Port::null()
        );

        $this->assertInstanceOf(Authority::class, $a);
        $this->assertSame($u, $a->userInformation());
        $this->assertSame($h, $a->host());
        $this->assertSame($p, $a->port());
        $this->assertSame('', (string) $a);

        $a = Authority::of(
            UserInformation::of(
                User::of('foo'),
                Password::of('bar')
            ),
            Host::of('localhost'),
            Port::of(8080)
        );

        $this->assertSame('foo:bar@localhost:8080', (string) $a);

        $a = Authority::of(
            UserInformation::of(
                User::null(),
                Password::null()
            ),
            Host::of('localhost'),
            Port::null()
        );

        $this->assertSame('localhost', (string) $a);
    }

    public function testWithUserInformation()
    {
        $authority = Authority::of(
            UserInformation::null(),
            Host::null(),
            Port::null()
        );
        $authority2 = $authority->withUserInformation(
            $info = UserInformation::null()
        );

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($info, $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithHost()
    {
        $authority = Authority::of(
            UserInformation::null(),
            Host::null(),
            Port::null()
        );
        $authority2 = $authority->withHost($host = Host::null());

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($host, $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithPort()
    {
        $authority = Authority::of(
            UserInformation::null(),
            Host::null(),
            Port::null()
        );
        $authority2 = $authority->withPort($port = Port::null());

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($port, $authority2->port());
    }


    public function testNullInterface()
    {
        $authority = Authority::null();

        $this->assertInstanceOf(
            Authority::class,
            $authority
        );
        $this->assertEquals(
            UserInformation::null(),
            $authority->userInformation()
        );
        $this->assertEquals(
            Host::null(),
            $authority->host()
        );
        $this->assertEquals(
            Port::null(),
            $authority->port()
        );
        $this->assertSame('', (string) $authority);
    }

    public function testNullWithUserInformation()
    {
        $authority = Authority::null();
        $authority2 = $authority->withUserInformation(
            $info = UserInformation::null()
        );

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($info, $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testNullWithHost()
    {
        $authority = Authority::null();
        $authority2 = $authority->withHost($host = Host::null());

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($host, $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testNullWithPort()
    {
        $authority = Authority::null();
        $authority2 = $authority->withPort($port = Port::null());

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($port, $authority2->port());
    }
}
