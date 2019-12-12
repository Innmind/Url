<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Scheme;
use Innmind\Url\Scheme as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class SchemeTest extends TestCase
{
    public function testInterface()
    {
        $set = Scheme::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Model::class, $value);
        }
    }
}
