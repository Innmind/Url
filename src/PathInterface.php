<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface PathInterface
{
    public function format(Query $query, Fragment $fragment): string;
    public function __toString(): string;
}
