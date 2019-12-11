<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    Host,
    HostInterface
};
use PHPUnit\Framework\TestCase;

class HostTest extends TestCase
{
    public function testInterface()
    {
        $h = Host::of($s = '[1:2:3::4:5:6:7]');

        $this->assertInstanceOf(HostInterface::class, $h);
        $this->assertSame($s, (string) $h);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidHost()
    {
        Host::of('foo bar');
    }

    public function testNull()
    {
        $this->assertInstanceOf(HostInterface::class, Host::null());
        $this->assertSame('', (string) Host::null());
    }
}
