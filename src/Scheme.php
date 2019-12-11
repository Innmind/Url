<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Scheme
{
    private const PATTERN = '/^[a-zA-Z0-9\-+.]+$/';
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

    public function format(Authority $authority): string
    {
        if ($this->value === '') {
            return $authority->toString();
        }

        return $this->value.'://'.$authority->toString();
    }

    public function toString(): string
    {
        return $this->value;
    }
}
