<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    Port,
    PortInterface
};

class PortTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $p = new Port(0);

        $this->assertInstanceOf(PortInterface::class, $p);
        $this->assertSame(0, $p->value());
        $this->assertSame('0', (string) $p);
    }
}