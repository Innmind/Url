<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\DomainException;
use Innmind\Immutable\Str;

final class Fragment
{
    private const PATTERN = '/^\S+$/';
    private $value;

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

    public function format(): string
    {
        if ($this->value === '') {
            return '';
        }

        return '#'.$this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
