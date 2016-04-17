<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\StringPrimitive as Str;

final class User implements UserInterface
{
    const PATTERN = '/^[\pL\pN-]+$/';
    private $value;

    public function __construct(string $value)
    {
        if (!(new Str($value))->match(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}