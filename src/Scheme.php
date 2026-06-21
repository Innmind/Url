<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class Scheme
{
    private function __construct(
        private string $value,
        private bool $less = false,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(string $value): self
    {
        if (\str_ends_with($value, '://')) {
            throw new \DomainException($value);
        }

        try {
            return Url::of($value.'://a.org/')->scheme();
        } catch (\Exception) {
            throw new \DomainException($value);
        }
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(
        Uri $parsed,
        #[\SensitiveParameter] string $origin,
    ): self {
        /** @psalm-suppress ImpureMethodCall */
        $scheme = $parsed->getScheme();

        return match ($scheme) {
            null => match (\str_starts_with($origin, '//')) {
                true => self::less(),
                false => self::none(),
            },
            default => new self($scheme),
        };
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsedUrl(
        Concrete $parsed,
        #[\SensitiveParameter] string $origin,
    ): self {
        /** @psalm-suppress ImpureMethodCall */
        $scheme = $parsed->getScheme();

        if (
            $scheme === 'http' &&
            \str_starts_with($origin, '//')
        ) {
            return self::less();
        }

        if (
            $scheme === 'http' &&
            !\str_starts_with($origin, 'http://')
        ) {
            return self::none();
        }

        return new self($scheme);
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
     * This will force the url to start with '//' unlike `::none()`
     *
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function less(): self
    {
        return new self('', true);
    }

    #[\NoDiscard]
    public function equals(self $scheme): bool
    {
        return $this->value === $scheme->value &&
            $this->less === $scheme->less;
    }

    #[\NoDiscard]
    public function format(Authority $authority): string
    {
        if ($this->less) {
            return '//'.$authority->toString();
        }

        if ($this->value === '') {
            return $authority->toString();
        }

        return $this->value.'://'.$authority->toString();
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value;
    }
}
