<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class NullFragment implements FragmentInterface
{
    public function __toString(): string
    {
        return '';
    }
}
