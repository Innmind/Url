<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\{
    Authority\UserInformation\UserInterface,
    Authority\UserInformation\NullUser,
    Authority\UserInformation\PasswordInterface,
    Authority\UserInformation\NullPassword,
    Exception\InvalidUserInformationException
};

final class UserInformation implements UserInformationInterface
{
    private $user;
    private $password;
    private $string;

    private function __construct(UserInterface $user, PasswordInterface $password)
    {
        if ($user instanceof NullUser && !$password instanceof NullPassword) {
            throw new InvalidUserInformationException;
        }

        $this->user = $user;
        $this->password = $password;
        $this->string = (string) $user;

        if (!$password instanceof NullPassword) {
            $this->string .= ':'.(string) $password;
        }
    }

    public static function of(UserInterface $user, PasswordInterface $password): self
    {
        return new self($user, $password);
    }

    public static function null(): UserInformationInterface
    {
        return new NullUserInformation;
    }

    public function user(): UserInterface
    {
        return $this->user;
    }

    public function withUser(UserInterface $user): UserInformationInterface
    {
        return new self($user, $this->password);
    }

    public function password(): PasswordInterface
    {
        return $this->password;
    }

    public function withPassword(PasswordInterface $password): UserInformationInterface
    {
        return new self($this->user, $password);
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
