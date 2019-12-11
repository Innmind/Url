<?php
declare(strict_types = 1);

namespace Innmind\Url;

interface UrlInterface
{
    public function scheme(): Scheme;
    public function withScheme(Scheme $scheme): self;
    public function authority(): AuthorityInterface;
    public function withAuthority(AuthorityInterface $authority): self;
    public function path(): PathInterface;
    public function withPath(PathInterface $path): self;
    public function query(): Query;
    public function withQuery(Query $query): self;
    public function fragment(): Fragment;
    public function withFragment(Fragment $fragment): self;
    public function __toString(): string;
}
