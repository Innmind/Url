<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class NullScheme implements SchemeInterface
{
    public function format(AuthorityInterface $authority): string
    {
        return (string) $authority;
    }

    public function __toString(): string
    {
        return '';
    }
}
