<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    Query,
    QueryInterface
};

class QueryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $f = new Query('foo');

        $this->assertInstanceOf(QueryInterface::class, $f);
        $this->assertSame('foo', (string) $f);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidArgumentException
     */
    public function testThrowWhenInvalidQuery()
    {
        new Query('foo bar');
    }
}
