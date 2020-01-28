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
use Fixtures\Innmind\Url\Authority as Fixture;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\PHPUnit\BlackBox;

class AuthorityTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $a = Authority::of(
            $u = UserInformation::of(
                User::none(),
                Password::none()
            ),
            $h = Host::none(),
            $p = Port::none()
        );

        $this->assertInstanceOf(Authority::class, $a);
        $this->assertSame($u, $a->userInformation());
        $this->assertSame($h, $a->host());
        $this->assertSame($p, $a->port());
        $this->assertSame('', $a->toString());

        $a = Authority::of(
            UserInformation::of(
                User::of('foo'),
                Password::of('bar')
            ),
            Host::of('localhost'),
            Port::of(8080)
        );

        $this->assertSame('foo:bar@localhost:8080', $a->toString());

        $a = Authority::of(
            UserInformation::of(
                User::none(),
                Password::none()
            ),
            Host::of('localhost'),
            Port::none()
        );

        $this->assertSame('localhost', $a->toString());
    }

    public function testWithUserInformation()
    {
        $authority = Authority::of(
            UserInformation::none(),
            Host::none(),
            Port::none()
        );
        $authority2 = $authority->withUserInformation(
            $info = UserInformation::none()
        );

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($info, $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithoutUserInformation()
    {
        $authority = Authority::of(
            UserInformation::of(
                User::of('foo'),
                Password::of('bar'),
            ),
            Host::none(),
            Port::none()
        );
        $authority2 = $authority->withoutUserInformation();

        $this->assertNotSame($authority, $authority2);
        $this->assertEquals(UserInformation::none(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithHost()
    {
        $authority = Authority::of(
            UserInformation::none(),
            Host::none(),
            Port::none()
        );
        $authority2 = $authority->withHost($host = Host::none());

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($host, $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithoutHost()
    {
        $authority = Authority::of(
            UserInformation::none(),
            Host::of('example.com'),
            Port::none()
        );
        $authority2 = $authority->withoutHost();

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertEquals(Host::none(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithPort()
    {
        $authority = Authority::of(
            UserInformation::none(),
            Host::none(),
            Port::none()
        );
        $authority2 = $authority->withPort($port = Port::none());

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($port, $authority2->port());
    }

    public function testWithoutPort()
    {
        $authority = Authority::of(
            UserInformation::none(),
            Host::none(),
            Port::of(8080)
        );
        $authority2 = $authority->withoutPort();

        $this->assertNotSame($authority, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertEquals(Port::none(), $authority2->port());
    }


    public function testNullInterface()
    {
        $authority = Authority::none();

        $this->assertInstanceOf(
            Authority::class,
            $authority
        );
        $this->assertEquals(
            UserInformation::none(),
            $authority->userInformation()
        );
        $this->assertEquals(
            Host::none(),
            $authority->host()
        );
        $this->assertEquals(
            Port::none(),
            $authority->port()
        );
        $this->assertSame('', $authority->toString());
    }

    public function testNullWithUserInformation()
    {
        $authority = Authority::none();
        $authority2 = $authority->withUserInformation(
            $info = UserInformation::none()
        );

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($info, $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testNullWithHost()
    {
        $authority = Authority::none();
        $authority2 = $authority->withHost($host = Host::none());

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($host, $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testNullWithPort()
    {
        $authority = Authority::none();
        $authority2 = $authority->withPort($port = Port::none());

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($port, $authority2->port());
    }

    public function testEquals()
    {
        $this
            ->forAll(Fixture::any(), Fixture::any())
            ->then(function($a, $b) {
                $this->assertTrue($a->equals($a));
                $this->assertTrue(
                    $a->equals(Authority::of(
                        $a->userInformation(),
                        $a->host(),
                        $a->port(),
                    )),
                );
                $this->assertFalse($a->equals($b));
                $this->assertFalse($b->equals($a));
            });
    }
}
