<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Fragment
{
    private const PATTERN = '/^\S+$/';

    private function __construct(private string $value)
    {
    }

    /**
     * @psalm-pure
     */
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
    public static function none(): self
    {
        return new self('');
    }

    public function equals(self $fragment): bool
    {
        return $this->value === $fragment->value;
    }

    public function format(): string
    {
        if ($this->value === '') {
            return '';
        }

        return '#'.$this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
