<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Scheme;
use Innmind\Url\Scheme as Model;
use Innmind\BlackBox\{
    PHPUnit\Framework\TestCase,
    Set,
    Random,
};

class SchemeTest extends TestCase
{
    /**
     * @group fixtures
     */
    public function testInterface()
    {
        $set = Scheme::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values(Random::default) as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->immutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
