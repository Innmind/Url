<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\DomainException;
use Innmind\Immutable\Str;

final class Path
{
    private const PATTERN = '~\S+~';
    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function of(string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new DomainException($value);
        }

        return new self($value);
    }

    public static function none(): self
    {
        return new self('');
    }

    public function absolute(): bool
    {
        return $this->toString()[0] === '/';
    }

    public function directory(): bool
    {
        return $this->toString()[-1] === '/';
    }

    public function resolve(self $path): self
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

    public function format(Query $query, Fragment $fragment): string
    {
        $end = $query->format().$fragment->format();

        if ($end === '' && $this->value === '') {
            return '/';
        }

        return $this->value.$end;
    }

    public function toString(): string
    {
        return $this->value === '' ? '/' : $this->value;
    }
}
