<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\{
    Authority\Host,
    Exception\DomainException,
};
use Innmind\Immutable\Str;

final class User
{
    private const PATTERN = '/^[\pL\pN-]+$/';
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

    public function format(Host $host, Password $password): string
    {
        if ($this->value === '') {
            return $host->toString();
        }

        return $password->format($this).'@'.$host->toString();
    }

    public function toString(): string
    {
        return $this->value;
    }
}
