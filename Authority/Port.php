<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority;

final class Port implements PortInterface
{
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
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
