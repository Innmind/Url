<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\DomainException;
use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
abstract class Path
{
    private const PATTERN = '~\S+~';
    private string $value;

    final private function __construct(string $value)
    {
        $this->value = $value;
    }

    /**
     * @psalm-pure
     */
    final public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new DomainException($value);
        }

        return $value[0] === '/' ? new AbsolutePath($value) : new RelativePath($value);
    }

    /**
     * @psalm-pure
     */
    final public static function none(): self
    {
        return new AbsolutePath('');
    }

    final public function equals(self $path): bool
    {
        return $this->value === $path->value;
    }

    abstract public function absolute(): bool;

    final public function directory(): bool
    {
        return $this->toString()[-1] === '/';
    }

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

    final public function format(Query $query, Fragment $fragment): string
    {
        $end = $query->format().$fragment->format();

        if ($end === '' && $this->value === '') {
            return '/';
        }

        return $this->value.$end;
    }

    final public function toString(): string
    {
        return $this->value === '' ? '/' : $this->value;
    }
}
