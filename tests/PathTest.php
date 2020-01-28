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

    public function testAbsolute()
    {
        $this->assertTrue(Path::of('/some/path')->absolute());
        $this->assertTrue(Path::none()->absolute());
        $this->assertFalse(Path::of('some/path')->absolute());
    }

    public function testDirectory()
    {
        $this->assertTrue(Path::of('/some/path/')->directory());
        $this->assertTrue(Path::none()->directory());
        $this->assertFalse(Path::of('/some/path')->directory());
    }

    public function testEquals()
    {
        $this->assertTrue(Path::none()->equals(Path::none()));
        $this->assertTrue(Path::of('/')->equals(Path::of('/')));
        $this->assertFalse(Path::of('/somewhere')->equals(Path::of('/')));
    }

    /**
     * @dataProvider resolutions
     */
    public function testResolve($expected, $source, $target)
    {
        $this->assertInstanceOf(Path::class, Path::of($source)->resolve(Path::of($target)));
        $this->assertSame($expected, Path::of($source)->resolve(Path::of($target))->toString());
    }

    public function resolutions(): array
    {
        return [
            'target is absolute' => [
                '/some/target',
                '/some/source',
                '/some/target',
            ],
            'absolute source is a directory' => [
                '/some/source/some/target',
                '/some/source/',
                'some/target',
            ],
            'relative source is a directory' => [
                'some/source/some/target',
                'some/source/',
                'some/target',
            ],
            'absolute source is a file' => [
                '/some/some/target',
                '/some/source',
                'some/target',
            ],
            'relative source is a file' => [
                'some/some/target',
                'some/source',
                'some/target',
            ],
        ];
    }
}
