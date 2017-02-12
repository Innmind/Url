<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url;

use Innmind\Url\{
    NullAuthority,
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
}
