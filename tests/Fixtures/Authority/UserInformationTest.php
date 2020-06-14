<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures\Authority;

use Fixtures\Innmind\Url\Authority\UserInformation;
use Innmind\Url\Authority\UserInformation as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    Set,
    Random\RandomInt,
};

class UserInformationTest extends TestCase
{
    public function testInterface()
    {
        $set = UserInformation::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values(new RandomInt) as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->isImmutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
