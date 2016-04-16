<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\{
    NullPassword,
    PasswordInterface
};

class NullPasswordTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $u = new NullPassword;

        $this->assertInstanceOf(PasswordInterface::class, $u);
        $this->assertSame('', (string) $u);
    }
}
