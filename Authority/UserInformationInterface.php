<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Authority\UserInformation\{
    UserInterface,
    PasswordInterface
};

interface UserInformationInterface
{
    public function user(): UserInterface;
    public function password(): PasswordInterface;
    public function __toString(): string;
}
