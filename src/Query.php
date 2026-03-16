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
            $url = $url->withQuery($value);

            /** @psalm-suppress ImpureMethodCall */
            return new self((string) $url->getQuery());
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
    public static function parsed(Uri $parsed): self
    {
        /** @psalm-suppress ImpureMethodCall */
        $query = $parsed->getQuery();

        return match ($query) {
            null => self::none(),
            default => new self($query),
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
