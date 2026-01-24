<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Uri\WhatWg\Url as Concrete;

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
            /** @psalm-suppress ImpureMethodCall */
            $url = new Concrete('http://a.org');
            /** @psalm-suppress ImpureMethodCall */
            $url = $url->withPassword($value);

            /** @psalm-suppress ImpureMethodCall */
            return new self((string) $url->getPassword());
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
