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
        $path = (string) $this->path;

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
     */
    public static function of(string $string): self
    {
        try {
            $data = Uri\parse(trim($string));
        } catch (\Exception $e) {
            throw new InvalidArgumentException;
        }

        return new self(
            $data['scheme'] ? Scheme::of($data['scheme']) : Scheme::null(),
            Authority::of(
                UserInformation::of(
                    $data['user'] ? User::of($data['user']) : User::null(),
                    $data['pass'] ? Password::of($data['pass']) : Password::null()
                ),
                $data['host'] ? Host::of($data['host']) : Host::null(),
                $data['port'] ? Port::of((int) $data['port']) : Port::null()
            ),
            $data['path'] && !empty($data['path']) ? Path::of($data['path']) : Path::null(),
            $data['query'] ? Query::of($data['query']) : Query::null(),
            $data['fragment'] ? Fragment::of($data['fragment']) : Fragment::null()
        );
    }
}
