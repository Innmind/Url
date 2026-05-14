<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Path,
    AbsolutePath,
    RelativePath,
};
use Fixtures\Innmind\Url\Path as FPath;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    PHPUnit\Framework\Attributes\DataProvider,
};

class PathTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $p = Path::of('/foo/bar/');

        $this->assertInstanceOf(Path::class, $p);
        $this->assertSame('/foo/bar/', $p->toString());

        $_ = Path::of('/'); //check it doesn't throw
        $_ = Path::of('relative/path');
    }

    public function testThrowWhenInvalidPath()
    {
        $this
            ->assert()
            ->throws(
                static fn() => Path::of(''),
                \DomainException::class,
            );
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

    #[DataProvider('resolutions')]
    public function testResolve($expected, $source, $target)
    {
        $this->assertInstanceOf(Path::class, Path::of($source)->resolve(Path::of($target)));
        $this->assertSame($expected, Path::of($source)->resolve(Path::of($target))->toString());
    }

    public function testNamedConstructorsReturnsAppropriateSubType()
    {
        $this->assertInstanceOf(AbsolutePath::class, Path::none());
        $this->assertInstanceOf(AbsolutePath::class, Path::of('/'));
        $this->assertInstanceOf(AbsolutePath::class, Path::of('/somewhere'));
        $this->assertInstanceOf(RelativePath::class, Path::of('somewhere'));
        $this->assertInstanceOf(RelativePath::class, Path::of('somewhere/'));
        $this->assertInstanceOf(RelativePath::class, Path::of('./somewhere'));
        $this->assertInstanceOf(RelativePath::class, Path::of('../somewhere'));
    }

    public function testAbsolutePathsAlwaysStartWithASlash(): BlackBox\Proof
    {
        return $this
            ->forAll(FPath::any())
            ->prove(function($path) {
                if ($path->absolute()) {
                    $this->assertStringStartsWith('/', $path->toString());
                } else {
                    $this->assertStringStartsNotWith('/', $path->toString());
                }
            });
    }

    public function testResolveAbsolutility(): BlackBox\Proof
    {
        return $this
            ->forAll(FPath::any(), FPath::any())
            ->prove(function($a, $b) {
                $this->assertSame(
                    $a->absolute() || $b->absolute(),
                    $a->resolve($b)->absolute(),
                );
            });
    }

    public function testPathStartingWithBackslashIsInvalid()
    {
        $this->assert()->throws(
            static fn() => Path::of('\\\\'),
        );
    }

    public static function resolutions(): array
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
            'relative to the root' => [
                '/target',
                '/source',
                'target',
            ],
        ];
    }
}
