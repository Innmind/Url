<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface SchemeInterface
{
    public function format(AuthorityInterface $authority): string;
    public function __toString(): string;
}
