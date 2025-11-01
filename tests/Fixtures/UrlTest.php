<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Url;
use Innmind\Url\Url as Model;
use Innmind\BlackBox\{
    PHPUnit\Framework\TestCase,
    Set,
    Random,
};

class UrlTest extends TestCase
{
    /**
     * @group fixtures
     */
    public function testInterface()
    {
        $set = Url::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values(Random::default) as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->immutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
