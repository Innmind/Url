<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\{
    InvalidArgumentException,
    InvalidUserInformationException,
};
use Innmind\Immutable\Str;

final class Password implements PasswordInterface
{
    private const PATTERN = '/^[\pL\pN-]+$/';
    private $value;

    private function __construct(string $value)
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public static function of(string $value): self
    {
        return new self($value);
    }

    public static function null(): PasswordInterface
    {
        return new NullPassword;
    }

    public function format(UserInterface $user): string
    {
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
