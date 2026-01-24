<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Authority\Host;
use Uri\WhatWg\Url as Concrete;

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
            /** @psalm-suppress ImpureMethodCall */
            $url = new Concrete('http://a.org');
            /** @psalm-suppress ImpureMethodCall */
            $url = $url->withUsername($value);

            /** @psalm-suppress ImpureMethodCall */
            return new self((string) $url->getUsername());
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
