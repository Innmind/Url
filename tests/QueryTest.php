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
        $this->assertSame('foo', (string) $f);
    }

    public function testNull()
    {
        $query = Query::null();

        $this->assertInstanceOf(Query::class, $query);
        $this->assertSame('', (string) $query);
    }
}
