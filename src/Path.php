<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Uri\WhatWg\Url as Concrete;

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
            /** @psalm-suppress ImpureMethodCall */
            $url = new Concrete('http://a.org/');
            /** @psalm-suppress ImpureMethodCall */
            $url = $url->withPath($value);

            return $value[0] === '/' ? new AbsolutePath($value) : new RelativePath($value);
        } catch (\Exception) {
            throw new \DomainException($value);
        }
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    final public static function none(): self
    {
        return new AbsolutePath('');
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
