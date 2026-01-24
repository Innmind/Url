<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\Fragment;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    public function testInterface()
    {
        $f = Fragment::of('foo');

        $this->assertInstanceOf(Fragment::class, $f);
        $this->assertSame('foo', $f->toString());
        $this->assertSame('foo%20bar', Fragment::of('foo bar')->toString());
    }

    public function testNull()
    {
        $this->assertInstanceOf(Fragment::class, Fragment::none());
        $this->assertSame('', Fragment::none()->toString());
    }

    public function testEquals()
    {
        $this->assertTrue(Fragment::none()->equals(Fragment::none()));
        $this->assertTrue(Fragment::of('foo')->equals(Fragment::of('foo')));
        $this->assertFalse(Fragment::of('bar')->equals(Fragment::of('foo')));
    }
}
