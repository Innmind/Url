<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Uri\Rfc3986\Uri as Concrete;

/**
 * @psalm-immutable
 */
final class Scheme
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
            $url = $url->withScheme($value);

            /** @psalm-suppress ImpureMethodCall */
            return new self((string) $url->getScheme());
        } catch (\Exception) {
            throw new \DomainException($value);
        }
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(Concrete $parsed): self
    {
        /** @psalm-suppress ImpureMethodCall */
        $scheme = $parsed->getScheme();

        return match ($scheme) {
            null => self::none(),
            default => new self($scheme),
        };
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
    public function equals(self $scheme): bool
    {
        return $this->value === $scheme->value;
    }

    #[\NoDiscard]
    public function format(Authority $authority): string
    {
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
