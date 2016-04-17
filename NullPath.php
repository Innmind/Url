<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class NullPath implements PathInterface
{
    public function __toString(): string
    {
        return '/';
    }
}
