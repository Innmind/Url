<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Query;
use Innmind\Url\Query as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class QueryTest extends TestCase
{
    public function testInterface()
    {
        $set = Query::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->isImmutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
