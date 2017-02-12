<?php
declare(strict_types = 1);

namespace Tests\Innmind\Url\Authority;

use Innmind\Url\Authority\{
    NullUserInformation,
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
}
