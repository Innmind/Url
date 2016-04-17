<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullFragment,
    FragmentInterface
};

class NullFragmentTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $f = new NullFragment;

        $this->assertInstanceOf(FragmentInterface::class, $f);
        $this->assertSame('', (string) $f);
    }
}
