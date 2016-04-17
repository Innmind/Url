<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    NullPort,
    PortInterface
};

class NullPortTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $p = new NullPort;

        $this->assertInstanceOf(PortInterface::class, $p);
        $this->assertSame(0, $p->value());
        $this->assertSame('', (string) $p);
    }
}
