<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests;

use Innmind\Url\{
    NullQuery,
    QueryInterface
};
use PHPUnit\Framework\TestCase;

class NullQueryTest extends TestCase
{
    public function testInterface()
    {
        $f = new NullQuery;

        $this->assertInstanceOf(QueryInterface::class, $f);
        $this->assertSame('', (string) $f);
    }
}
