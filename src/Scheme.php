<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Scheme implements SchemeInterface
{
    private const PATTERN = '/^[a-zA-Z0-9\-+.]+$/';
    private $value;

    private function __construct(string $value)
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public static function of(string $value): self
    {
        return new self($value);
    }

    public static function null(): SchemeInterface
    {
        return new NullScheme;
    }

    public function format(AuthorityInterface $authority): string
    {
        return $this->value.'://'.$authority;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
