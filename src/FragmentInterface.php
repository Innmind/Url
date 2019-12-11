<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface FragmentInterface
{
    public function format(): string;
    public function __toString(): string;
}
