<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

final class Port implements PortInterface
{
    private $value;

    private function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function of(int $value): self
    {
        return new self($value);
    }

    public static function null(): PortInterface
    {
        return new NullPort;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
