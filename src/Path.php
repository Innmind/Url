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
    final private function __construct(private string $value)
    {
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
            $path = Url::of($value)->path();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        if ($path instanceof RelativePath) {
            return new RelativePath($value);
        }

        return new AbsolutePath($value);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    final public static function none(): self
    {
        return new AbsolutePath('');
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
            return new AbsolutePath($path);
        }

        return new RelativePath($path);
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
            default => new RelativePath($path),
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
