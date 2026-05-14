<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Path;
use Innmind\Url\Path as Model;
use Innmind\BlackBox\{
    PHPUnit\Framework\TestCase,
    Set,
    Random,
};

class PathTest extends TestCase
{
    /**
     * @group fixtures
     */
    public function testInterface()
    {
        $set = Path::any()->take(100);

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values(Random::default) as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }

    /**
     * @group fixtures
     */
    public function testDirectories()
    {
        $set = Path::directories()->take(100);

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values(Random::default) as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertInstanceOf(Model::class, $value->unwrap());
            $this->assertTrue($value->unwrap()->directory());
        }
    }
}
