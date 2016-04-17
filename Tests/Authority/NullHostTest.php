<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    NullHost,
    HostInterface
};

class NullHostTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $h = new NullHost;

        $this->assertInstanceOf(HostInterface::class, $h);
        $this->assertSame('', (string) $h);
    }
}
