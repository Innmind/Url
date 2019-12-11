<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\{
    DomainException,
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
            throw new DomainException($value);
        }

        return new self($value);
    }

    public static function none(): self
    {
        return new self('');
    }

    public function format(User $user): string
    {
        if ($this->value === '') {
            return $user->toString();
        }

        if ($user->toString() === '') {
            throw new InvalidUserInformationException;
        }

        return $user->toString().':'.$this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
