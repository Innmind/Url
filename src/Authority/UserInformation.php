<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\{
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
};

final class UserInformation
{
    private $user;
    private $password;
    private $string;

    private function __construct(User $user, Password $password)
    {
        $this->user = $user;
        $this->password = $password;
        $this->string = $password->format($user);
    }

    public static function of(User $user, Password $password): self
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

    public function user(): User
    {
        return $this->user;
    }

    public function withUser(User $user): self
    {
        return new self($user, $this->password);
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function withPassword(Password $password): self
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
