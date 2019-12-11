<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\{
    InvalidArgumentException,
    InvalidUserInformationException,
};
use Innmind\Immutable\Str;

final class Password
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

    public static function null(): self
    {
        return new self('');
    }

    public function format(UserInterface $user): string
    {
        if ($this->value === '') {
            return (string) $user;
        }

        if ((string) $user === '') {
            throw new InvalidUserInformationException;
        }

        return "$user:{$this->value}";
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
