<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Fixtures\Authority\UserInformation;

use Fixtures\Innmind\Url\Authority\UserInformation\User;
use Innmind\Url\Authority\UserInformation\User as Model;
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\Set;

class UserTest extends TestCase
{
    public function testInterface()
    {
        $set = User::any();

        $this->assertInstanceOf(Set::class, $set);

        foreach ($set->values() as $value) {
            $this->assertInstanceOf(Model::class, $value);
        }
    }
}
