<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

final class NullPassword implements PasswordInterface
{
    public function __toString(): string
    {
        return '';
    }
}
