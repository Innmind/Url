<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\{
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\NullUser,
    Authority\UserInformation\Password,
    Authority\UserInformation\NullPassword,
    Authority\Host,
    Authority\NullHost,
    Authority\Port,
    Authority\NullPort,
    Exception\InvalidArgumentException
};
use League\Uri;

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

    public function withScheme(SchemeInterface $scheme): UrlInterface
    {
        $self = clone $this;
        $self->scheme = $scheme;

        return $self;
    }

    public function authority(): AuthorityInterface
    {
        return $this->authority;
    }

    public function withAuthority(AuthorityInterface $authority): UrlInterface
    {
        $self = clone $this;
        $self->authority = $authority;

        return $self;
    }

    public function path(): PathInterface
    {
        return $this->path;
    }

    public function withPath(PathInterface $path): UrlInterface
    {
        $self = clone $this;
        $self->path = $path;

        return $self;
    }

    public function query(): QueryInterface
    {
        return $this->query;
    }

    public function withQuery(QueryInterface $query): UrlInterface
    {
        $self = clone $this;
        $self->query = $query;

        return $self;
    }

    public function fragment(): FragmentInterface
    {
        return $this->fragment;
    }

    public function withFragment(FragmentInterface $fragment): UrlInterface
    {
        $self = clone $this;
        $self->fragment = $fragment;

        return $self;
    }

    public function __toString(): string
    {
        $path = $path = (string) $this->path;

        if (
            $this->path instanceof NullPath &&
            (
                !$this->query instanceof NullQuery ||
                !$this->fragment instanceof NullFragment
            )
        ) {
            $path = '';
        }

        return sprintf(
            '%s%s%s%s%s',
            $this->scheme,
            !$this->scheme instanceof NullScheme ? '://'.$this->authority : $this->authority,
            $path,
            !$this->query instanceof NullQuery ? '?'.$this->query : '',
            !$this->fragment instanceof NullFragment ? '#'.$this->fragment : ''
        );
    }

    /**
     * Build a url out of the given string
     *
     * @param string $string
     *
     * @return self
     */
    public static function of(string $string): self
    {
        try {
            $data = Uri\parse(trim($string));
        } catch (\Exception $e) {
            throw new InvalidArgumentException;
        }

        return new self(
            $data['scheme'] ? new Scheme($data['scheme']) : new NullScheme,
            new Authority(
                new UserInformation(
                    $data['user'] ? new User($data['user']) : new NullUser,
                    $data['pass'] ? new Password($data['pass']) : new NullPassword
                ),
                $data['host'] ? new Host($data['host']) : new NullHost,
                $data['port'] ? new Port((int) $data['port']) : new NullPort
            ),
            $data['path'] && !empty($data['path']) ? new Path($data['path']) : new NullPath,
            $data['query'] ? Query::of($data['query']) : new NullQuery,
            $data['fragment'] ? new Fragment($data['fragment']) : new NullFragment
        );
    }

    /**
     * @deprecated
     * @see self::of()
     */
    public static function fromString(string $string): self
    {
        return self::of($string);
    }
}
