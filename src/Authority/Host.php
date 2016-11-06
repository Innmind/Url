<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

use Innmind\Url\Exception\InvalidArgumentException;
use Innmind\Immutable\StringPrimitive as Str;

final class Host implements HostInterface
{
    const PATTERN = '~^\S+$~ix';
    private $value;

    public function __construct(string $value)
    {
        if (!(new Str($value))->match(self::PATTERN)) {
            throw new InvalidArgumentException;
        }

        $this->value = $value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
