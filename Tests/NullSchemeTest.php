<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullScheme,
    SchemeInterface
};

class NullSchemeTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $s = new NullScheme;

        $this->assertInstanceOf(SchemeInterface::class, $s);
        $this->assertSame('', (string) $s);
    }
}
