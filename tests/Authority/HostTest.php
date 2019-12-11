<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\{
    Authority\Host,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    public function testInterface()
    {
        $h = Host::of($s = '[1:2:3::4:5:6:7]');

        $this->assertInstanceOf(Host::class, $h);
        $this->assertSame($s, $h->toString());
    }

    public function testThrowWhenInvalidHost()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('foo bar');

        Host::of('foo bar');
    }

    public function testNull()
    {
        $this->assertInstanceOf(Host::class, Host::none());
        $this->assertSame('', Host::none()->toString());
    }
}
