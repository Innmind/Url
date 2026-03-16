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
use Uri\Rfc3986\Uri;

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
        private _Url|Uri|null $parsed,
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
            null,
        );
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(#[\SensitiveParameter] string $string): self
    {
        $self = self::tryUri($string);

        if (!\is_null($self)) {
            return $self;
        }

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
            null,
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
            null,
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
            null,
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
            null,
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
            null,
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
            null,
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
            null,
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
            null,
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
            null,
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
            null,
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
    private static function tryUri(#[\SensitiveParameter] string $string): ?self
    {
        $uri = Uri::parse($string);

        if (\is_null($uri)) {
            return null;
        }

        return new self(
            Scheme::parsed($uri),
            Authority::of(
                UserInformation::of(
                    User::parsed($uri),
                    Password::parsed($uri),
                ),
                Host::parsed($uri),
                Port::parsed($uri),
            ),
            Path::parsed($uri),
            Query::parsed($uri),
            Fragment::parsed($uri),
            $uri,
        );
    }

    /**
     * @psalm-pure
     * @psalm-suppress ImpureMethodCall
     */
    private static function tryUrl(#[\SensitiveParameter] string $string): self
    {
        $string = \trim($string);
        $url = new _Url($string, new _Url('http://a.org'));
        $scheme = Scheme::parsedUrl($url, $string);
        $user = User::parsed($url);
        $password = Password::parsed($url);
        $host = Host::parsedUrl($url, $string);
        $port = Port::parsed($url);
        $path = Path::parsed($url);
        $query = Query::parsed($url);
        $fragment = Fragment::parsed($url);

        if ($host->toString() === 'a.org' && \str_contains($string, 'a.org')) {
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
            $path = Path::initiallyRelative($path);
        }

        return new self(
            $scheme,
            $authority,
            $path,
            $query,
            $fragment,
            $url,
        );
    }
}
