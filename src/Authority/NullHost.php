<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

final class NullHost implements HostInterface
{
    public function __toString(): string
    {
        return '';
    }
}
