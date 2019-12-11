<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Exception\InvalidArgumentException;
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
            throw new InvalidArgumentException;
        }

        return new self($value);
    }

    public static function none(): self
    {
        return new self('');
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
