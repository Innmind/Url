<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    Host,
    HostInterface
};

class HostTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $h = new Host($s = '[1:2:3::4:5:6:7]');

        $this->assertInstanceOf(HostInterface::class, $h);
        $this->assertSame($s, (string) $h);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidHost()
    {
        new Host('foo bar');
    }
}
