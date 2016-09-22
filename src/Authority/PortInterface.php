<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

interface PortInterface
{
    public function value(): int;
    public function __toString(): string;
}
