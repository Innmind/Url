<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullPath,
    PathInterface
};

class NullPathInterface extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $p = new NullPath;

        $this->assertInstanceOf(PathInterface::class, $p);
        $this->assertSame('/', (string) $p);
    }
}
