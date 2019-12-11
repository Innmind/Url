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
        Scheme $scheme,
        Authority $authority,
        Path $path,
        Query $query,
        Fragment $fragment
    ) {
        $this->scheme = $scheme;
        $this->authority = $authority;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    public function scheme(): Scheme
    {
        return $this->scheme;
    }

    public function withScheme(Scheme $scheme): UrlInterface
    {
        $self = clone $this;
        $self->scheme = $scheme;

        return $self;
    }

    public function authority(): Authority
    {
        return $this->authority;
    }

    public function withAuthority(Authority $authority): UrlInterface
    {
        $self = clone $this;
        $self->authority = $authority;

        return $self;
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function withPath(Path $path): UrlInterface
    {
        $self = clone $this;
        $self->path = $path;

        return $self;
    }

    public function query(): Query
    {
        return $this->query;
    }

    public function withQuery(Query $query): UrlInterface
    {
        $self = clone $this;
        $self->query = $query;

        return $self;
    }

    public function fragment(): Fragment
    {
        return $this->fragment;
    }

    public function withFragment(Fragment $fragment): UrlInterface
    {
        $self = clone $this;
        $self->fragment = $fragment;

        return $self;
    }

    public function __toString(): string
    {
        return $this->scheme->format($this->authority).$this->path->format($this->query, $this->fragment);
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
