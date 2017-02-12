<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\{
    NullUser,
    UserInterface
};
use PHPUnit\Framework\TestCase;

class NullUserTest extends TestCase
{
    public function testInterface()
    {
        $u = new NullUser;

        $this->assertInstanceOf(UserInterface::class, $u);
        $this->assertSame('', (string) $u);
    }
}
