<?php
declare(strict_types = 1);

namespace Innmind\Url\Tests\Authority;

use Innmind\Url\Authority\{
    UserInformation,
    UserInformation\User,
    UserInformation\Password,
};
use PHPUnit\Framework\TestCase;

class UserInformationTest extends TestCase
{
    public function testInterface()
    {
        $ui = UserInformation::of(
            $u = User::of('foo'),
            $p = Password::of('bar')
        );

        $this->assertInstanceOf(UserInformation::class, $ui);
        $this->assertSame($u, $ui->user());
        $this->assertSame($p, $ui->password());
        $this->assertSame('foo:bar', (string) $ui);
    }

    /**
     * @expectedException Innmind\Url\Exception\InvalidUserInformationException
     */
    public function testThrowWhenNullUserButPasswordPresent()
    {
        UserInformation::of(User::none(), Password::of('foo'));
    }

    public function testString()
    {
        $this->assertSame(
            'foo',
            (string )UserInformation::of(User::of('foo'), Password::none())
        );
        $this->assertSame(
            '',
            (string) UserInformation::of(User::none(), Password::none())
        );
    }

    public function testWithUser()
    {
        $info = UserInformation::of(User::of('foo'), Password::of('bar'));
        $info2 = $info->withUser($user = User::of('baz'));

        $this->assertNotSame($info, $info2);
        $this->assertSame($user, $info2->user());
        $this->assertSame($info->password(), $info2->password());
    }

    public function testWithPassword()
    {
        $info = UserInformation::of(User::of('foo'), Password::of('bar'));
        $info2 = $info->withPassword($password = Password::of('baz'));

        $this->assertNotSame($info, $info2);
        $this->assertSame($info->user(), $info2->user());
        $this->assertSame($password, $info2->password());
    }

    public function testNullWithUser()
    {
        $info = UserInformation::none();
        $info2 = $info->withUser($user = User::none());

        $this->assertNotSame($info, $info2);
        $this->assertInstanceOf(UserInformation::class, $info2);
        $this->assertSame($user, $info2->user());
        $this->assertSame($info->password(), $info2->password());
    }

    public function testNullWithPassword()
    {
        $info = UserInformation::none();
        $info2 = $info->withPassword($password = Password::none());

        $this->assertNotSame($info, $info2);
        $this->assertInstanceOf(UserInformation::class, $info2);
        $this->assertSame($info->user(), $info2->user());
        $this->assertSame($password, $info2->password());
    }
}
