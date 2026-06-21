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
    /**
     * @param ?\WeakReference<Uri|Concrete> $parsed
     */
    private function __construct(
        private string $value,
        private ?\WeakReference $parsed,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(string $value): self
    {
        try {
            // this variable is here to keep a reference to the underlying
            // parsed object
            $url = Url::of('http://a.org/?'.$value);
            $self = $url->query();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        /** @psalm-suppress ImpureMethodCall */
        $parsed = $self->parsed?->get();

        if (\is_null($parsed)) {
            return $self;
        }

        /** @psalm-suppress ImpureMethodCall */
        if (!\is_null($parsed->getFragment())) {
            throw new \DomainException($value);
        }

        return $self;
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

        /** @psalm-suppress ImpureMethodCall */
        return match ($query) {
            null => self::none(),
            default => new self($query, \WeakReference::create($parsed)),
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
