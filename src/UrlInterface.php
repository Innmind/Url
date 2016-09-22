<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface UrlInterface
{
    public function scheme(): SchemeInterface;
    public function authority(): AuthorityInterface;
    public function path(): PathInterface;
    public function query(): QueryInterface;
    public function fragment(): FragmentInterface;
    public function __toString(): string;
}
