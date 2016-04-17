<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class Url implements UrlInterface
{
    private $scheme;
    private $authority;
    private $path;
    private $query;
    private $fragment;

    public function __construct(
        SchemeInterface $scheme,
        AuthorityInterface $authority,
        PathInterface $path,
        QueryInterface $query,
        FragmentInterface $fragment
    ) {
        $this->scheme = $scheme;
        $this->authority = $authority;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    public function scheme(): SchemeInterface
    {
        return $this->scheme;
    }

    public function authority(): AuthorityInterface
    {
        return $this->authority;
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function query(): QueryInterface
    {
        return $this->query;
    }

    public function fragment(): FragmentInterface
    {
        return $this->fragment;
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s%s%s',
            $this->scheme,
            !$this->scheme instanceof NullScheme ? '://' . $this->authority : '',
            $this->path,
            !$this->query instanceof NullQuery ? '?' . $this->query : '',
            !$this->fragment instanceof NullFragment ? '#' . $this->fragment : ''
        );
    }
}
