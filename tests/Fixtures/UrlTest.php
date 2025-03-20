<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures;

use Fixtures\Innmind\Url\Url;
use Innmind\Url\Url as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
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

            if (\interface_exists(Set\Implementation::class)) {
                $this->assertTrue($value->immutable());
            } else {
                $this->assertTrue($value->isImmutable());
            }

            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
