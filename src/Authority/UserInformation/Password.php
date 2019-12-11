<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Password implements PasswordInterface
{
    const PATTERN = '/^[\pL\pN-]+$/';
    private $value;

    public function __construct(string $value)
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
