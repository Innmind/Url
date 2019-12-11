<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testInterface()
    {
        $p = Path::of('/foo/bar/');

        $this->assertInstanceOf(Path::class, $p);
        $this->assertSame('/foo/bar/', (string) $p);

        Path::of('/'); //check it doesn't throw
        Path::of('relative/path');
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidPath()
    {
        Path::of('');
    }

    public function testNull()
    {
        $path = Path::null();

        $this->assertInstanceOf(Path::class, $path);
        $this->assertSame('/', (string) $path);
    }
}
