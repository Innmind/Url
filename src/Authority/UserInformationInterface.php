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
    public function withUser(UserInterface $user): self;
    public function password(): PasswordInterface;
    public function withPassword(PasswordInterface $password): self;
    public function format(Host $host): string;
    public function __toString(): string;
}
