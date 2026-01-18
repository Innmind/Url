<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Scheme
{
    private const PATTERN = '/^[a-zA-Z0-9\-+.]+$/';

    private function __construct(private string $value)
    {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new \DomainException($value);
        }

        return new self($value);
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
