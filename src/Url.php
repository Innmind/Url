<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\{
    Authority\UserInformation,
    Authority\UserInformation\User,
    Authority\UserInformation\Password,
    Authority\Host,
    Authority\Port,
};
use Innmind\Immutable\{
    Maybe,
    Attempt,
};
use League\Uri;

/**
 * @psalm-immutable
 */
final class Url
{
    private function __construct(
        private Scheme $scheme,
        private Authority $authority,
        private Path $path,
        private Query $query,
        private Fragment $fragment,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function from(
        Scheme $scheme,
        Authority $authority,
        Path $path,
        Query $query,
        Fragment $fragment,
    ): self {
        return new self(
            $scheme,
            $authority,
            $path,
            $query,
            $fragment,
        );
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(#[\SensitiveParameter] string $string): self
    {
        try {
            /**
             * @psalm-suppress ImpureFunctionCall
             * @var array{scheme: ?string, user: ?string, pass: ?string, host: ?string, port: ?string, path: ?string, query: ?string, fragment: ?string}
             */
            $data = Uri\parse(\trim($string));
        } catch (\Exception $e) {
            throw new \DomainException;
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
    #[\NoDiscard]
    public static function maybe(#[\SensitiveParameter] string $string): Maybe
    {
        return self::attempt($string)->maybe();
    }

    /**
     * Similar to self::of() but will return nothing instead of throwing an
     * exception
     *
     * @psalm-pure
     *
     * @return Attempt<self>
     */
    #[\NoDiscard]
    public static function attempt(#[\SensitiveParameter] string $string): Attempt
    {
        return Attempt::of(static fn() => self::of($string));
    }

    #[\NoDiscard]
    public function equals(self $url): bool
    {
        return $this->scheme->equals($url->scheme()) &&
            $this->authority->equals($url->authority()) &&
            $this->path->equals($url->path()) &&
            $this->query->equals($url->query()) &&
            $this->fragment->equals($url->fragment());
    }

    #[\NoDiscard]
    public function scheme(): Scheme
    {
        return $this->scheme;
    }

    #[\NoDiscard]
    public function withScheme(Scheme $scheme): self
    {
        return new self(
            $scheme,
            $this->authority,
            $this->path,
            $this->query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function withoutScheme(): self
    {
        return new self(
            Scheme::none(),
            $this->authority,
            $this->path,
            $this->query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function authority(): Authority
    {
        return $this->authority;
    }

    #[\NoDiscard]
    public function withAuthority(Authority $authority): self
    {
        return new self(
            $this->scheme,
            $authority,
            $this->path,
            $this->query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function withoutAuthority(): self
    {
        return new self(
            $this->scheme,
            Authority::none(),
            $this->path,
            $this->query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function path(): Path
    {
        return $this->path;
    }

    #[\NoDiscard]
    public function withPath(Path $path): self
    {
        return new self(
            $this->scheme,
            $this->authority,
            $path,
            $this->query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function withoutPath(): self
    {
        return new self(
            $this->scheme,
            $this->authority,
            Path::none(),
            $this->query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function query(): Query
    {
        return $this->query;
    }

    #[\NoDiscard]
    public function withQuery(Query $query): self
    {
        return new self(
            $this->scheme,
            $this->authority,
            $this->path,
            $query,
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function withoutQuery(): self
    {
        return new self(
            $this->scheme,
            $this->authority,
            $this->path,
            Query::none(),
            $this->fragment,
        );
    }

    #[\NoDiscard]
    public function fragment(): Fragment
    {
        return $this->fragment;
    }

    #[\NoDiscard]
    public function withFragment(Fragment $fragment): self
    {
        return new self(
            $this->scheme,
            $this->authority,
            $this->path,
            $this->query,
            $fragment,
        );
    }

    #[\NoDiscard]
    public function withoutFragment(): self
    {
        return new self(
            $this->scheme,
            $this->authority,
            $this->path,
            $this->query,
            Fragment::none(),
        );
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->scheme->format($this->authority).$this->path->format($this->query, $this->fragment);
    }
}
