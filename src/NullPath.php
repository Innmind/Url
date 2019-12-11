<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class NullPath implements PathInterface
{
    public function format(Query $query, Fragment $fragment): string
    {
        $end = $query->format().$fragment->format();

        if ($end !== '') {
            return $end;
        }

        return '/';
    }

    public function __toString(): string
    {
        return '/';
    }
}
