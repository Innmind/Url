<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Exception\DomainException;
use Innmind\Immutable\Str;
use League\Uri;

final class Query
{
    private const PATTERN = '/^\S+$/';
    private $value;

    private function __construct(string $value)
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new DomainException($value);
        }

        $this->value = $value;
    }

    public static function of(string $value): self
    {
        try {
            return new self($value);
        } catch (DomainException $e) {
            return new self(
                Uri\build_query(
                    Uri\parse_query($value)
                )
            );
        }
    }

    public static function none(): self
    {
        $self = new self('void');
        $self->value = '';

        return $self;
    }

    public function format(): string
    {
        if ($this->value === '') {
            return '';
        }

        return '?'.$this->value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
