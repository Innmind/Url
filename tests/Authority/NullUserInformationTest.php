<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Authority;

use Innmind\Url\Authority\{
    NullUserInformation,
    UserInformation,
    UserInformationInterface,
    UserInformation\NullUser,
    UserInformation\NullPassword
};
use PHPUnit\Framework\TestCase;

class NullUserInformationTest extends TestCase
{
    public function testInterface()
    {
        $info = new NullUserInformation;

        $this->assertInstanceOf(
            UserInformationInterface::class,
            $info
        );
        $this->assertInstanceOf(
            NullUser::class,
            $info->user()
        );
        $this->assertInstanceOf(
            NullPassword::class,
            $info->password()
        );
        $this->assertSame('', (string) $info);
    }

    public function testWithUser()
    {
        $info = new NullUserInformation;
        $info2 = $info->withUser($user = new NullUser);

        $this->assertNotSame($info, $info2);
        $this->assertInstanceOf(UserInformation::class, $info2);
        $this->assertSame($user, $info2->user());
        $this->assertSame($info->password(), $info2->password());
    }

    public function testWithPassword()
    {
        $info = new NullUserInformation;
        $info2 = $info->withPassword($password = new NullPassword);

        $this->assertNotSame($info, $info2);
        $this->assertInstanceOf(UserInformation::class, $info2);
        $this->assertSame($info->user(), $info2->user());
        $this->assertSame($password, $info2->password());
    }
}
