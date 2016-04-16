<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface SchemeInterface
{
    public function __toString(): string;
}
