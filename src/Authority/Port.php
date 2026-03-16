<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class Port
{
    private function __construct(private ?int $value)
    {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(int $value): self
    {
        if ($value < 0) {
            throw new \DomainException((string) $value);
        }

        return new self($value);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self(null);
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(Uri $parsed): self
    {
        /** @psalm-suppress ImpureMethodCall */
        $port = $parsed->getPort();

        return match ($port) {
            null => self::none(),
            default => new self($port),
        };
    }

    #[\NoDiscard]
    public function equals(self $port): bool
    {
        return $this->value === $port->value;
    }

    #[\NoDiscard]
    public function format(): string
    {
        if ($this->value === null) {
            return '';
        }

        return ':'.$this->value;
    }

    #[\NoDiscard]
    public function value(): int
    {
        return match ($this->value) {
            null => 0,
            default => $this->value,
        };
    }

    #[\NoDiscard]
    public function toString(): string
    {
        if ($this->value === null) {
            return '';
        }

        return (string) $this->value;
    }
}
