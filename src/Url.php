<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\{
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
    Authority\Host,
    Authority\Port,
    Exception\DomainException,
};
use League\Uri;

final class Url
{
    private Scheme $scheme;
    private Authority $authority;
    private Path $path;
    private Query $query;
    private Fragment $fragment;

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

    public static function of(string $string): self
    {
        try {
            $data = Uri\parse(trim($string));
        } catch (\Exception $e) {
            throw new DomainException($string);
        }

        return new self(
            $data['scheme'] ? Scheme::of($data['scheme']) : Scheme::none(),
            Authority::of(
                UserInformation::of(
                    $data['user'] ? User::of($data['user']) : User::none(),
                    $data['pass'] ? Password::of($data['pass']) : Password::none(),
                ),
                $data['host'] ? Host::of($data['host']) : Host::none(),
                $data['port'] ? Port::of((int) $data['port']) : Port::none(),
            ),
            $data['path'] && !empty($data['path']) ? Path::of($data['path']) : Path::none(),
            $data['query'] ? Query::of($data['query']) : Query::none(),
            $data['fragment'] ? Fragment::of($data['fragment']) : Fragment::none(),
        );
    }

    public function equals(self $url): bool
    {
        return $this->scheme->equals($url->scheme()) &&
            $this->authority->equals($url->authority()) &&
            $this->path->equals($url->path()) &&
            $this->query->equals($url->query()) &&
            $this->fragment->equals($url->fragment());
    }

    public function scheme(): Scheme
    {
        return $this->scheme;
    }

    public function withScheme(Scheme $scheme): self
    {
        $self = clone $this;
        $self->scheme = $scheme;

        return $self;
    }

    public function withoutScheme(): self
    {
        $self = clone $this;
        $self->scheme = Scheme::none();

        return $self;
    }

    public function authority(): Authority
    {
        return $this->authority;
    }

    public function withAuthority(Authority $authority): self
    {
        $self = clone $this;
        $self->authority = $authority;

        return $self;
    }

    public function withoutAuthority(): self
    {
        $self = clone $this;
        $self->authority = Authority::none();

        return $self;
    }

    public function path(): Path
    {
        return $this->path;
    }

    public function withPath(Path $path): self
    {
        $self = clone $this;
        $self->path = $path;

        return $self;
    }

    public function withoutPath(): self
    {
        $self = clone $this;
        $self->path = Path::none();

        return $self;
    }

    public function query(): Query
    {
        return $this->query;
    }

    public function withQuery(Query $query): self
    {
        $self = clone $this;
        $self->query = $query;

        return $self;
    }

    public function withoutQuery(): self
    {
        $self = clone $this;
        $self->query = Query::none();

        return $self;
    }

    public function fragment(): Fragment
    {
        return $this->fragment;
    }

    public function withFragment(Fragment $fragment): self
    {
        $self = clone $this;
        $self->fragment = $fragment;

        return $self;
    }

    public function withoutFragment(): self
    {
        $self = clone $this;
        $self->fragment = Fragment::none();

        return $self;
    }

    public function toString(): string
    {
        return $this->scheme->format($this->authority).$this->path->format($this->query, $this->fragment);
    }
}
