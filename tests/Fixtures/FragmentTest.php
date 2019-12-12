<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Fragment;
use Innmind\Url\Fragment as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class FragmentTest extends TestCase
{
    public function testInterface()
    {
        $set = Fragment::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Model::class, $value);
        }
    }
}
