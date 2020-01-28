<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\{
    Authority\Port,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class PortTest extends TestCase
{
    public function testInterface()
    {
        $p = Port::of(0);

        $this->assertInstanceOf(Port::class, $p);
        $this->assertSame(0, $p->value());
        $this->assertSame('0', $p->toString());
    }

    public function testThrowWhenNegativePort()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('-1');

        Port::of(-1);
    }

    public function testNull()
    {
        $this->assertInstanceOf(Port::class, Port::none());
        $this->assertSame(0, Port::none()->value());
        $this->assertSame('', Port::none()->toString());
    }

    public function testEquals()
    {
        $this->assertTrue(Port::none()->equals(Port::none()));
        $this->assertTrue(Port::of(80)->equals(Port::of(80)));
        $this->assertFalse(Port::of(8080)->equals(Port::of(80)));
    }
}
