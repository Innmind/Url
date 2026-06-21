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
    private function __construct(
        private string $value,
        private Uri|Concrete|null $parsed,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(string $value): self
    {
        try {
            $self = Url::of(\sprintf(
                'http://%s@a.org',
                $value,
            ))
                ->authority()
                ->userInformation()
                ->user();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        if (\is_null($self->parsed)) {
            // it means the Url parsed the user as null and the provided value
            // as been parsed as another component
            if ($value !== '') {
                throw new \DomainException;
            }

            return $self;
        }

        /** @psalm-suppress ImpureMethodCall */
        if (
            !\in_array($self->parsed->getPassword(), ['', null], true) ||
            !\in_array($self->parsed->getPath(), ['/', '', null], true) ||
            !\is_null($self->parsed->getQuery()) ||
            !\is_null($self->parsed->getFragment())
        ) {
            throw new \DomainException($value);
        }

        // the parsed object is longer necessary at this point
        return new self($self->value, null);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self('', null);
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(Uri|Concrete $parsed): self
    {
        if ($parsed instanceof Uri) {
            /** @psalm-suppress ImpureMethodCall */
            $user = $parsed->getRawUsername();
        } else {
            /** @psalm-suppress ImpureMethodCall */
            $user = $parsed->getUsername();
        }

        return match ($user) {
            null => self::none(),
            default => new self($user, $parsed),
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
