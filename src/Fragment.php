<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class Fragment
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
            $url = $url->withFragment($value);

            /** @psalm-suppress ImpureMethodCall */
            return new self((string) $url->getFragment());
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
        $fragment = $parsed->getFragment();

        return match ($fragment) {
            null => self::none(),
            default => new self($fragment),
        };
    }

    #[\NoDiscard]
    public function equals(self $fragment): bool
    {
        return $this->value === $fragment->value;
    }

    #[\NoDiscard]
    public function format(): string
    {
        if ($this->value === '') {
            return '';
        }

        return '#'.$this->value;
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value;
    }
}
