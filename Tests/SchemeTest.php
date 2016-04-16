<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Scheme,
    SchemeInterface
};

class SchemeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $s = new Scheme('http-2.0');

        $this->assertInstanceOf(SchemeInterface::class, $s);
        $this->assertSame('http-2.0', (string) $s);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidData()
    {
        new Scheme('http://');
    }
}
