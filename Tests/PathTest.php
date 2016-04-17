<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Path,
    PathInterface
};

class PathTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $p = new Path('/foo/bar/');

        $this->assertInstanceOf(PathInterface::class, $p);
        $this->assertSame('/foo/bar/', (string) $p);

        new Path('/'); //check it doesn't throw
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidPath()
    {
        new Path('');
    }
}
