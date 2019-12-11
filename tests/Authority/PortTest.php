<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\Port;
use PHPUnit\Framework\TestCase;

class PortTest extends TestCase
{
    public function testInterface()
    {
        $p = Port::of(0);

        $this->assertInstanceOf(Port::class, $p);
        $this->assertSame(0, $p->value());
        $this->assertSame('0', (string) $p);
    }

    public function testNull()
    {
        $this->assertInstanceOf(Port::class, Port::null());
        $this->assertSame(0, Port::null()->value());
        $this->assertSame('', (string) Port::null());
    }
}
