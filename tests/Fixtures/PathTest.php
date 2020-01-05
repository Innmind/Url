<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Path;
use Innmind\Url\Path as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class PathTest extends TestCase
{
    public function testInterface()
    {
        $set = Path::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->isImmutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
