<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\{
    Url,
    Authority\Host,
};
use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class User
{
    private function __construct(private string $value)
    {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(string $value): self
    {
        try {
            return Url::of(\sprintf(
                'http://%s@a.org',
                $value,
            ))
                ->authority()
                ->userInformation()
                ->user();
        } catch (\Exception) {
            throw new \DomainException($value);
        }
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self('');
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(Uri|Concrete $parsed): self
    {
        /** @psalm-suppress ImpureMethodCall */
        $user = $parsed->getUsername();

        return match ($user) {
            null => self::none(),
            default => new self($user),
        };
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
