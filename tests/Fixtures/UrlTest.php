<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Url;
use Innmind\Url\Url as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class UrlTest extends TestCase
{
    public function testInterface()
    {
        $set = Url::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Model::class, $value);
        }
    }
}
