<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

interface PasswordInterface
{
    public function format(UserInterface $user): string;
    public function __toString(): string;
}
