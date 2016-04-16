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

    public function __construct(UserInterface $user, PasswordInterface $password)
    {
        if ($user instanceof NullUser && !$password instanceof NullPassword) {
            throw new InvalidUserInformationException;
        }

        $this->user = $user;
        $this->password = $password;
        $this->string = (string) $user;

        if (!$password instanceof NullPassword) {
            $this->string .= ':' . (string) $password;
        }
    }

    public function user(): UserInterface
    {
        return $this->user;
    }

    public function password(): PasswordInterface
    {
        return $this->password;
    }

    public function __toString(): string
    {
        return $this->string;
    }
}
