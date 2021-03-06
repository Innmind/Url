<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\Query;
use PHPUnit\Framework\TestCase;

class QueryTest extends TestCase
{
    public function testInterface()
    {
        $f = Query::of('foo');

        $this->assertInstanceOf(Query::class, $f);
        $this->assertSame('foo', $f->toString());
    }

    public function testNull()
    {
        $query = Query::none();

        $this->assertInstanceOf(Query::class, $query);
        $this->assertSame('', $query->toString());
    }

    public function testEquals()
    {
        $this->assertTrue(Query::none()->equals(Query::none()));
        $this->assertTrue(Query::of('foo')->equals(Query::of('foo')));
        $this->assertFalse(Query::of('bar')->equals(Query::of('foo')));
    }
}
