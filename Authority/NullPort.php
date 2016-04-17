<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

final class NullPort implements PortInterface
{
    public function value(): int
    {
        return 0;
    }

    public function __toString(): string
    {
        return '';
    }
}
