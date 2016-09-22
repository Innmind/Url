<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

interface HostInterface
{
    public function __toString(): string;
}
