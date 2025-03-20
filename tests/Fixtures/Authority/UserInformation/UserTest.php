<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures\Authority\UserInformation;

use Fixtures\Innmind\Url\Authority\UserInformation\User;
use Innmind\Url\Authority\UserInformation\User as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    Set,
    Random,
};

class UserTest extends TestCase
{
    /**
     * @group fixtures
     */
    public function testInterface()
    {
        $set = User::any();

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
