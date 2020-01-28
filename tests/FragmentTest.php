<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Fragment,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    public function testInterface()
    {
        $f = Fragment::of('foo');

        $this->assertInstanceOf(Fragment::class, $f);
        $this->assertSame('foo', $f->toString());
    }

    public function testThrowWhenInvalidFragment()
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('foo bar');

        Fragment::of('foo bar');
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
