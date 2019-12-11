<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Authority\UserInformation\{
    UserInterface,
    PasswordInterface,
    NullUser,
    NullPassword
};

final class NullUserInformation implements UserInformationInterface
{
    private $user;
    private $password;

    public function __construct()
    {
        $this->user = new NullUser;
        $this->password = new NullPassword;
    }

    public function user(): UserInterface
    {
        return $this->user;
    }

    public function withUser(UserInterface $user): UserInformationInterface
    {
        return UserInformation::of($user, $this->password);
    }

    public function password(): PasswordInterface
    {
        return $this->password;
    }

    public function withPassword(PasswordInterface $password): UserInformationInterface
    {
        return UserInformation::of($this->user, $password);
    }

    public function format(Host $host): string
    {
        return (string) $host;
    }

    public function __toString(): string
    {
        return '';
    }
}
