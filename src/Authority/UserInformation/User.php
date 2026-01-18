<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Authority\Host;
use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class User
{
    private const PATTERN = '/^[\pL\pN-]+$/';

    private function __construct(private string $value)
    {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new \DomainException($value);
        }

        return new self($value);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self('');
    }

    #[\NoDiscard]
    public function equals(self $user): bool
    {
        return $this->value === $user->value;
    }

    #[\NoDiscard]
    public function format(Host $host, Password $password): string
    {
        if ($this->value === '') {
            return $host->toString();
        }

        return $password->format($this).'@'.$host->toString();
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value;
    }
}
