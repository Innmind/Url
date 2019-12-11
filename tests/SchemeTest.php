<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\Scheme;
use PHPUnit\Framework\TestCase;

class SchemeTest extends TestCase
{
    public function testInterface()
    {
        $s = Scheme::of('http-2.0');

        $this->assertInstanceOf(Scheme::class, $s);
        $this->assertSame('http-2.0', $s->toString());
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidData()
    {
        Scheme::of('http://');
    }

    public function testNull()
    {
        $scheme = Scheme::none();

        $this->assertInstanceOf(Scheme::class, $scheme);
        $this->assertSame('', $scheme->toString());
    }
}
