<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class NullQuery implements QueryInterface
{
    public function __toString(): string
    {
        return '';
    }
}
