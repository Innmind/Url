<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\Fragment;
use PHPUnit\Framework\TestCase;

class FragmentTest extends TestCase
{
    public function testInterface()
    {
        $f = Fragment::of('foo');

        $this->assertInstanceOf(Fragment::class, $f);
        $this->assertSame('foo', $f->toString());
    }

    /**
     * @expectedException Innmind\Url\Exception\DomainException
     */
    public function testThrowWhenInvalidFragment()
    {
        Fragment::of('foo bar');
    }

    public function testNull()
    {
        $this->assertInstanceOf(Fragment::class, Fragment::none());
        $this->assertSame('', Fragment::none()->toString());
    }
}
