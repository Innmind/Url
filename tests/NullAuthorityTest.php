<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url;

use Innmind\Url\{
    NullAuthority,
    Authority,
    AuthorityInterface,
    Authority\NullUserInformation,
    Authority\NullHost,
    Authority\NullPort
};
use PHPUnit\Framework\TestCase;

class NullAuthorityTest extends TestCase
{
    public function testInterface()
    {
        $authority = new NullAuthority;

        $this->assertInstanceOf(
            AuthorityInterface::class,
            $authority
        );
        $this->assertInstanceOf(
            NullUserInformation::class,
            $authority->userInformation()
        );
        $this->assertInstanceOf(
            NullHost::class,
            $authority->host()
        );
        $this->assertInstanceOf(
            NullPort::class,
            $authority->port()
        );
        $this->assertSame('', (string) $authority);
    }

    public function testWithUserInformation()
    {
        $authority = new NullAuthority;
        $authority2 = $authority->withUserInformation(
            $info = new NullUserInformation
        );

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($info, $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithHost()
    {
        $authority = new NullAuthority;
        $authority2 = $authority->withHost($host = new NullHost);

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($host, $authority2->host());
        $this->assertSame($authority->port(), $authority2->port());
    }

    public function testWithPort()
    {
        $authority = new NullAuthority;
        $authority2 = $authority->withPort($port = new NullPort);

        $this->assertNotSame($authority, $authority2);
        $this->assertInstanceOf(Authority::class, $authority2);
        $this->assertSame($authority->userInformation(), $authority2->userInformation());
        $this->assertSame($authority->host(), $authority2->host());
        $this->assertSame($port, $authority2->port());
    }
}
