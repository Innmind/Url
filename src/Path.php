<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
abstract class Path
{
    /**
     * @param ?\WeakReference<Uri|Concrete> $parsed
     */
    final private function __construct(
        private string $value,
        private ?\WeakReference $parsed,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    final public static function of(string $value): self
    {
        if ($value === '') {
            throw new \DomainException;
        }

        try {
            // this variable is here to keep a reference to the underlying
            // parsed object
            $url = Url::of($value);
            $path = $url->path();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        /** @psalm-suppress ImpureMethodCall */
        $parsed = $path->parsed?->get();

        if (!\is_null($parsed)) {
            /** @psalm-suppress ImpureMethodCall */
            if (!\is_null($parsed->getQuery())) {
                throw new \DomainException($value);
            }

            /** @psalm-suppress ImpureMethodCall */
            if (!\is_null($parsed->getFragment())) {
                throw new \DomainException($value);
            }
        }

        if ($path instanceof RelativePath) {
            return new RelativePath($value, null);
        }

        // For some paths the parsing removes chars leading to the parsing
        // thinking the path is absolute when the specified one is relative.
        // But since we don't use the parsed string, to avoid using encoded
        // strings, then the relative path is returned as an absolute one.
        if ($value[0] !== '/') {
            return new RelativePath($value, null);
        }

        return new AbsolutePath($value, null);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    final public static function none(): self
    {
        return new AbsolutePath('', null);
    }

    /**
     * @internal
     * @psalm-pure
     */
    final public static function parsed(Uri|Concrete $parsed): self
    {
        if ($parsed instanceof Uri) {
            /** @psalm-suppress ImpureMethodCall */
            $path = $parsed->getRawPath();
        } else {
            /** @psalm-suppress ImpureMethodCall */
            $path = $parsed->getPath();
        }

        if ($path === '') {
            return self::none();
        }

        if ($path[0] === '/') {
            /** @psalm-suppress ImpureMethodCall */
            return new AbsolutePath($path, \WeakReference::create($parsed));
        }

        /** @psalm-suppress ImpureMethodCall */
        return new RelativePath($path, \WeakReference::create($parsed));
    }

    /**
     * @internal
     * @psalm-pure
     */
    final public static function initiallyRelative(self $self): self
    {
        $path = \substr($self->toString(), 1);

        return match ($path) {
            '' => self::none(),
            default => new RelativePath($path, $self->parsed),
        };
    }

    #[\NoDiscard]
    final public function equals(self $path): bool
    {
        return $this->value === $path->value;
    }

    abstract public function absolute(): bool;

    #[\NoDiscard]
    final public function directory(): bool
    {
        return $this->toString()[-1] === '/';
    }

    #[\NoDiscard]
    final public function resolve(self $path): self
    {
        if ($path->absolute()) {
            return $path;
        }

        if ($this->directory()) {
            return self::of($this->toString().$path->toString());
        }

        $parent = \dirname($this->toString());

        if ($parent === '/') {
            return self::of('/'.$path->toString());
        }

        return self::of($parent.'/'.$path->toString());
    }

    #[\NoDiscard]
    final public function format(Query $query, Fragment $fragment): string
    {
        $end = $query->format().$fragment->format();

        if ($end === '' && $this->value === '') {
            return '/';
        }

        return $this->value.$end;
    }

    #[\NoDiscard]
    final public function toString(): string
    {
        return $this->value === '' ? '/' : $this->value;
    }
}
