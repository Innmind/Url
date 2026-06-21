<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Url;
use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class Host
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
            $url = Url::of('http://'.$value);
            $self = $url
                ->authority()
                ->host();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        /** @psalm-suppress ImpureMethodCall */
        $parsed = $self->parsed?->get();

        if (\is_null($parsed)) {
            return $self;
        }

        /** @psalm-suppress ImpureMethodCall */
        if (
            !\is_null($parsed->getPort()) ||
            !\in_array($parsed->getPath(), ['/', '', null], true) ||
            !\is_null($parsed->getQuery()) ||
            !\is_null($parsed->getFragment())
        ) {
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
    public static function parsed(Uri $parsed): self
    {
        /** @psalm-suppress ImpureMethodCall */
        $host = $parsed->getRawHost();

        /** @psalm-suppress ImpureMethodCall */
        return match ($host) {
            null => self::none(),
            default => new self($host, \WeakReference::create($parsed)),
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
        $host = $parsed->getUnicodeHost();

        if (!\is_null($host) && !\str_contains($origin, $host)) {
            /** @psalm-suppress ImpureMethodCall */
            $host = $parsed->getAsciiHost();
        }

        if ($host === 'a.org' && !\str_contains($origin, 'a.org')) {
            return self::none();
        }

        /** @psalm-suppress ImpureMethodCall */
        return match ($host) {
            null => self::none(),
            default => new self($host, \WeakReference::create($parsed)),
        };
    }

    #[\NoDiscard]
    public function equals(self $host): bool
    {
        return $this->value === $host->value;
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value;
    }
}
