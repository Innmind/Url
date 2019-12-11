<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface UrlInterface
{
    public function scheme(): Scheme;
    public function withScheme(Scheme $scheme): self;
    public function authority(): Authority;
    public function withAuthority(Authority $authority): self;
    public function path(): Path;
    public function withPath(Path $path): self;
    public function query(): Query;
    public function withQuery(Query $query): self;
    public function fragment(): Fragment;
    public function withFragment(Fragment $fragment): self;
    public function __toString(): string;
}
