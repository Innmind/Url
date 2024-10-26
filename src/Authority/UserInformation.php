<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\{
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
};

/**
 * @psalm-immutable
 */
final class UserInformation
{
    private User $user;
    private Password $password;

    private function __construct(User $user, Password $password)
    {
        // Make sure a user is specified when a password is specified
        $password->format($user);
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * @psalm-pure
     */
    public static function of(User $user, Password $password): self
    {
        return new self($user, $password);
    }

    /**
     * @psalm-pure
     */
    public static function none(): self
    {
        return new self(
            User::none(),
            Password::none(),
        );
    }

    public function equals(self $userInformation): bool
    {
        return $this->user->equals($userInformation->user()) &&
            $this->password->equals($userInformation->password());
    }

    public function user(): User
    {
        return $this->user;
    }

    public function withUser(User $user): self
    {
        return new self($user, $this->password);
    }

    public function withoutUser(): self
    {
        return new self(User::none(), $this->password);
    }

    public function password(): Password
    {
        return $this->password;
    }

    public function withPassword(Password $password): self
    {
        return new self($this->user, $password);
    }

    public function withoutPassword(): self
    {
        return new self($this->user, Password::none());
    }

    public function format(Host $host): string
    {
        return $this->user->format($host, $this->password);
    }

    public function toString(): string
    {
        return $this->password->format($this->user);
    }
}
