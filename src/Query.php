<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Query implements QueryInterface
{
    const PATTERN = '/^\S+$/';
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
