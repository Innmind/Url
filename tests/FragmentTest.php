<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Fragment,
    FragmentInterface
};
use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    public function testInterface()
    {
        $f = Fragment::of('foo');

        $this->assertInstanceOf(FragmentInterface::class, $f);
        $this->assertSame('foo', (string) $f);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidFragment()
    {
        Fragment::of('foo bar');
    }

    public function testNull()
    {
        $this->assertInstanceOf(FragmentInterface::class, Fragment::null());
        $this->assertSame('', (string) Fragment::null());
    }
}
