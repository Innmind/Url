<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullPath,
    PathInterface
};
use PHPUnit\Framework\TestCase;

class NullPathInterface extends TestCase
{
    public function testInterface()
    {
        $p = new NullPath;

        $this->assertInstanceOf(PathInterface::class, $p);
        $this->assertSame('/', (string) $p);
    }
}
