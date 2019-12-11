<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class User
{
    private const PATTERN = '/^[\pL\pN-]+$/';
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        return new self($value);
    }

    public static function none(): self
    {
        return new self('');
    }

    public function toString(): string
    {
        return $this->value;
    }
}
