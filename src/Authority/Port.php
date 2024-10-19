<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Exception\DomainException;

/**
 * @psalm-immutable
 */
final class Port
{
    private ?int $value;

    private function __construct(?int $value)
    {
        if (\is_int($value) && $value < 0) {
            throw new DomainException((string) $value);
        }

        $this->value = $value;
    }

    /**
     * @psalm-pure
     */
    public static function of(int $value): self
    {
        return new self($value);
    }

    /**
     * @psalm-pure
     */
    public static function none(): self
    {
        return new self(null);
    }

    public function equals(self $port): bool
    {
        return $this->value === $port->value;
    }

    public function format(): string
    {
        if ($this->value === null) {
            return '';
        }

        return ':'.$this->value;
    }

    public function value(): int
    {
        return match ($this->value) {
            null => 0,
            default => $this->value,
        };
    }

    public function toString(): string
    {
        if ($this->value === null) {
            return '';
        }

        return (string) $this->value;
    }
}
