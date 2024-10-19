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
use Innmind\Immutable\Maybe;
use League\Uri;

/**
 * @psalm-immutable
 */
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
        Fragment $fragment,
    ) {
        $this->scheme = $scheme;
        $this->authority = $authority;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    /**
     * @psalm-pure
     */
    public static function of(string $string): self
    {
        try {
            /**
             * @psalm-suppress ImpureFunctionCall
             * @var array{scheme: ?string, user: ?string, pass: ?string, host: ?string, port: ?string, path: ?string, query: ?string, fragment: ?string}
             */
            $data = Uri\parse(\trim($string));
        } catch (\Exception $e) {
            throw new DomainException($string);
        }

        return new self(
            match ($data['scheme']) {
                null, '' => Scheme::none(),
                default => Scheme::of($data['scheme']),
            },
            Authority::of(
                UserInformation::of(
                    match ($data['user']) {
                        null, '' => User::none(),
                        default => User::of($data['user']),
                    },
                    match ($data['pass']) {
                        null, '' => Password::none(),
                        default => Password::of($data['pass']),
                    },
                ),
                match ($data['host']) {
                    null, '' => Host::none(),
                    default => Host::of($data['host']),
                },
                match ($data['port']) {
                    null, '' => Port::none(),
                    default => Port::of((int) $data['port']),
                },
            ),
            match ($data['path']) {
                null, '' => Path::none(),
                default => Path::of($data['path']),
            },
            match ($data['query']) {
                null, '' => Query::none(),
                default => Query::of($data['query']),
            },
            match ($data['fragment']) {
                null, '' => Fragment::none(),
                default => Fragment::of($data['fragment']),
            },
        );
    }

    /**
     * Similar to self::of() but will return nothing instead of throwing an
     * exception
     *
     * @psalm-pure
     *
     * @return Maybe<self>
     */
    public static function maybe(string $string): Maybe
    {
        try {
            return Maybe::just(self::of($string));
        } catch (DomainException $e) {
            /** @var Maybe<self> */
            return Maybe::nothing();
        }
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
