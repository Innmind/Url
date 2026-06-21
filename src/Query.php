<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class Query
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
            $self = Url::of('http://a.org/?'.$value)->query();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        if (\is_null($self->parsed)) {
            return $self;
        }

        /** @psalm-suppress ImpureMethodCall */
        if (!\is_null($self->parsed->getFragment())) {
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
            $query = $parsed->getRawQuery();
        } else {
            /** @psalm-suppress ImpureMethodCall */
            $query = $parsed->getQuery();
        }

        return match ($query) {
            null => self::none(),
            default => new self($query, $parsed),
        };
    }

    #[\NoDiscard]
    public function equals(self $query): bool
    {
        return $this->value === $query->value;
    }

    #[\NoDiscard]
    public function format(): string
    {
        if ($this->value === '') {
            return '';
        }

        return '?'.$this->value;
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value;
    }
}
