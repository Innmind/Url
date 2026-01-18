<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Authority;
use Innmind\Url\Authority as Model;
use Innmind\BlackBox\{
    PHPUnit\Framework\TestCase,
    Set,
    Random,
};

class AuthorityTest extends TestCase
{
    /**
     * @group fixtures
     */
    public function testInterface()
    {
        $set = Authority::any();

        $this->assertInstanceOf(Set\Provider::class, $set);

        foreach ($set->toSet()->values(Random::default) as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->immutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
