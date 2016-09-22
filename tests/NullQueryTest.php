<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullQuery,
    QueryInterface
};

class NullQueryTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $f = new NullQuery;

        $this->assertInstanceOf(QueryInterface::class, $f);
        $this->assertSame('', (string) $f);
    }
}
