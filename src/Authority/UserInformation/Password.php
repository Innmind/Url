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
    private Uri|Concrete|null $parsed;

    private function __construct(string $value, Uri|Concrete|null $parsed)
    {
        $this->value = new \SensitiveParameterValue($value);
        $this->parsed = $parsed;
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(#[\SensitiveParameter] string $value): self
    {
        try {
            $self = Url::of(\sprintf(
                'http://u:%s@a.org',
                $value,
            ))
                ->authority()
                ->userInformation()
                ->password();
        } catch (\Exception) {
            throw new \DomainException($value);
        }

        if (\is_null($self->parsed)) {
            return $self;
        }

        /** @psalm-suppress ImpureMethodCall */
        if (
            !\in_array($self->parsed->getPath(), ['/', '', null], true) ||
            !\is_null($self->parsed->getQuery()) ||
            !\is_null($self->parsed->getFragment())
        ) {
            throw new \DomainException($value);
        }

        // the parsed object is longer necessary at this point
        return new self($self->value->getValue(), null);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self('', null);
    }

    /**
     * @internal
     * @psalm-pure
     */
    public static function parsed(Uri|Concrete $parsed): self
    {
        if ($parsed instanceof Uri) {
            /** @psalm-suppress ImpureMethodCall */
            $password = $parsed->getRawPassword();
        } else {
            /** @psalm-suppress ImpureMethodCall */
            $password = $parsed->getPassword();
        }

        return match ($password) {
            null => self::none(),
            default => new self($password, $parsed),
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
