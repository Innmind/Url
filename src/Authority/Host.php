<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\Str;

final class Host implements HostInterface
{
    private const PATTERN = '~^\S+$~ix';
    private $value;

    public function __construct(string $value)
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
