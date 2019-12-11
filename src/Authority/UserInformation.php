<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\{
    Authority\UserInformation\UserInterface,
    Authority\UserInformation\NullUser,
    Authority\UserInformation\User,
    Authority\UserInformation\PasswordInterface,
    Authority\UserInformation\Password,
    Authority\UserInformation\NullPassword,
};

final class UserInformation
{
    private $user;
    private $password;
    private $string;

    private function __construct(UserInterface $user, PasswordInterface $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->string = $password->format($user);
    }

    public static function of(UserInterface $user, PasswordInterface $password): self
    {
        return new self($user, $password);
    }

    public static function null(): self
    {
        return new self(
            User::null(),
            Password::null(),
        );
    }

    public function user(): UserInterface
    {
        return $this->user;
    }

    public function withUser(UserInterface $user): self
    {
        return new self($user, $this->password);
    }

    public function password(): PasswordInterface
    {
        return $this->password;
    }

    public function withPassword(PasswordInterface $password): self
    {
        return new self($this->user, $password);
    }

    public function format(Host $host): string
    {
        if ((string) $this->user === '') {
            return (string) $host;
        }

        return "{$this->string}@$host";
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
