<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Exception\{
    DomainException,
    PasswordCannotBeSpecifiedWithoutAUser,
};
use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Password
{
    private const PATTERN = '/^[\pL\pN-]+$/';
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new DomainException($value);
        }

        return new self($value);
    }

    /**
     * @psalm-pure
     */
    public static function none(): self
    {
        return new self('');
    }

    public function equals(self $password): bool
    {
        return $this->value === $password->value;
    }

    public function format(User $user): string
    {
        if ($this->value === '') {
            return $user->toString();
        }

        if ($user->toString() === '') {
            throw new PasswordCannotBeSpecifiedWithoutAUser;
        }

        return $user->toString().':'.$this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
