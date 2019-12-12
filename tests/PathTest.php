<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Path,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testInterface()
    {
        $p = Path::of('/foo/bar/');

        $this->assertInstanceOf(Path::class, $p);
        $this->assertSame('/foo/bar/', $p->toString());

        Path::of('/'); //check it doesn't throw
        Path::of('relative/path');
    }

    public function testThrowWhenInvalidPath()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('');

        Path::of('');
    }

    public function testNull()
    {
        $path = Path::none();

        $this->assertInstanceOf(Path::class, $path);
        $this->assertSame('/', $path->toString());
    }
}
