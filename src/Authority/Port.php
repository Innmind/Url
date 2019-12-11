<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

final class Port
{
    private $value;

    private function __construct(?int $value)
    {
        $this->value = $value;
    }

    public static function of(int $value): self
    {
        return new self($value);
    }

    public static function none(): self
    {
        return new self(null);
    }

    public function format(): string
    {
        if ($this->value === null) {
            return '';
        }

        return ':'.$this->value;
    }

    public function value(): int
    {
        return $this->value ?: 0;
    }

    public function toString(): string
    {
        if ($this->value === null) {
            return '';
        }

        return (string) $this->value;
    }
}
