<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;
use League\Uri;

final class Query implements QueryInterface
{
    private const PATTERN = '/^\S+$/';
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
        try {
            return new self($value);
        } catch (InvalidArgumentException $e) {
            return new self(
                Uri\build_query(
                    Uri\parse_query($value)
                )
            );
        }
    }

    public static function null(): QueryInterface
    {
        return new NullQuery;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
