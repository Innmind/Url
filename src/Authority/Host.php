<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Exception\DomainException;
use Innmind\Immutable\Str;

final class Host
{
    private const PATTERN = '~^\S+$~ix';
    private $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new DomainException($value);
        }

        return new self($value);
    }

    public static function none(): self
    {
        return new self('');
    }

    public function toString(): string
    {
        return $this->value;
    }
}
