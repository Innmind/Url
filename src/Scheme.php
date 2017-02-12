<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Scheme implements SchemeInterface
{
    const PATTERN = '/^[a-zA-Z0-9\-+.]+$/';
    private $value;

    public function __construct(string $value)
    {
        if (!(new Str($value))->matches(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
