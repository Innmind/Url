<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Url\Url;
use Uri\WhatWg\Url as Concrete;
use Uri\Rfc3986\Uri;

/**
 * @psalm-immutable
 */
final class Password
{
    /** @var \SensitiveParameterValue<string> */
    private \SensitiveParameterValue $value;

    private function __construct(string $value)
    {
        $this->value = new \SensitiveParameterValue($value);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(#[\SensitiveParameter] string $value): self
    {
        try {
            return Url::of(\sprintf(
                'http://u:%s@a.org',
                $value,
            ))
                ->authority()
                ->userInformation()
                ->password();
        } catch (\Exception) {
            throw new \DomainException($value);
        }
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self('');
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(Uri|Concrete $parsed): self
    {
        /** @psalm-suppress ImpureMethodCall */
        $password = $parsed->getPassword();

        return match ($password) {
            null => self::none(),
            default => new self($password),
        };
    }

    #[\NoDiscard]
    public function equals(self $password): bool
    {
        return $this->value->getValue() === $password->value->getValue();
    }

    #[\NoDiscard]
    public function format(User $user): string
    {
        if ($this->value->getValue() === '') {
            return $user->toString();
        }

        if ($user->toString() === '') {
            throw new \DomainException('Password cannot be specified without a user');
        }

        return $user->toString().':'.(string) $this->value->getValue();
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->value->getValue();
    }
}
