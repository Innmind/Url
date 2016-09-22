<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

final class NullUser implements UserInterface
{
    public function __toString(): string
    {
        return '';
    }
}
