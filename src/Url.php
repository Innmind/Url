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
use Uri\WhatWg\Url as _Url;

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
            return self::tryUrl($string);
        } catch (\Exception $e) {
            throw new \DomainException;
        }
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

    /**
     * @psalm-pure
     * @psalm-suppress ImpureMethodCall
     */
    private static function tryUrl(#[\SensitiveParameter] string $string): self
    {
        $string = \trim($string);
        $url = new _Url($string, new _Url('http://a.org'));
        $scheme = $url->getScheme();
        $user = $url->getUsername();
        $password = $url->getPassword();
        $host = $url->getUnicodeHost();
        $port = $url->getPort();
        $path = $url->getPath();
        $query = $url->getQuery();
        $fragment = $url->getFragment();

        if (!\is_null($host) && !\str_contains($string, $host)) {
            $host = $url->getAsciiHost();
        }

        if (
            $scheme === 'http' &&
            !\str_starts_with($string, 'http://')
        ) {
            $scheme = null;
        }

        $scheme = match ($scheme) {
            null => Scheme::none(),
            default => Scheme::of($scheme),
        };
        $user = match ($user) {
            null => User::none(),
            default => User::of($user),
        };
        $password = match ($password) {
            null => Password::none(),
            default => Password::of($password),
        };
        $host = match ($host) {
            null => Host::none(),
            default => Host::of($host),
        };
        $port = match ($port) {
            null => Port::none(),
            default => Port::of($port),
        };
        $path = match ($path) {
            '' => Path::none(),
            default => Path::of($path),
        };
        $query = match ($query) {
            null => Query::none(),
            default => Query::of($query),
        };
        $fragment = match ($fragment) {
            null => Fragment::none(),
            default => Fragment::of($fragment),
        };

        if ($host->toString() === 'a.org') {
            if (!\str_contains($string, 'a.org')) {
                $host = Host::none();
            } else {
                $start = self::from(
                    $scheme,
                    Authority::of(
                        UserInformation::of($user, $password),
                        $host,
                        $port,
                    ),
                    Path::none(),
                    Query::none(),
                    Fragment::none(),
                );
                $withScheme = $start->toString();
                $schemeLess = '//'.$start
                    ->withoutScheme()
                    ->toString();

                if (
                    !\str_starts_with($string, $withScheme) &&
                    !\str_starts_with($string, $schemeLess)
                ) {
                    $host = Host::none();
                }
            }
        }

        $authority = Authority::of(
            UserInformation::of(
                $user,
                $password,
            ),
            $host,
            $port,
        );

        if (
            $scheme->equals(Scheme::none()) &&
            $authority->equals(Authority::none()) &&
            !\str_starts_with($string, '/')
        ) {
            $path = \substr($path->toString(), 1);
            $path = match ($path) {
                '' => Path::none(),
                default => Path::of($path),
            };
        }

        return self::from(
            $scheme,
            $authority,
            $path,
            $query,
            $fragment,
        );
    }
}
