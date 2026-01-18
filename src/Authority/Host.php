<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Host
{
    private const PATTERN = '~^\S+$~ix';

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
