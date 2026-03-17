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
            return Url::of('http://'.$value)
                ->authority()
                ->host();
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
        $host = $parsed->getRawHost();

        return match ($host) {
            null => self::none(),
            default => new self($host),
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

        return match ($host) {
            null => self::none(),
            default => new self($host),
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
