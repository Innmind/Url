<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullScheme,
    SchemeInterface
};
use PHPUnit\Framework\TestCase;

class NullSchemeTest extends TestCase
{
    public function testInterface()
    {
        $s = new NullScheme;

        $this->assertInstanceOf(SchemeInterface::class, $s);
        $this->assertSame('', (string) $s);
    }
}
