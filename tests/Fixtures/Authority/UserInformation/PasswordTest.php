<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures\Authority\UserInformation;

use Fixtures\Innmind\Url\Authority\UserInformation\Password;
use Innmind\Url\Authority\UserInformation\Password as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class PasswordTest extends TestCase
{
    public function testInterface()
    {
        $set = Password::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Set\Value::class, $value);
            $this->assertTrue($value->isImmutable());
            $this->assertInstanceOf(Model::class, $value->unwrap());
        }
    }
}
