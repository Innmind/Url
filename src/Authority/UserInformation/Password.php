<?php
declare(strict_types = 1);

namespace Innmind\Url\Authority\UserInformation;

use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Password
{
    private const PATTERN = '/^[\pL\pN-]+$/';
    /** @var \SensitiveParameterValue<string> */
    private \SensitiveParameterValue $value;

    private function __construct(string $value)
    {
        $this->value = new \SensitiveParameterValue($value);
    }

    /**
     * @psalm-pure
     */
    public static function of(#[\SensitiveParameter] string $value): self
    {
        if (!Str::of($value)->matches(self::PATTERN)) {
            throw new \DomainException;
        }

        return new self($value);
    }

    /**
     * @psalm-pure
     */
    public static function none(): self
    {
        return new self('');
    }

    public function equals(self $password): bool
    {
        return $this->value->getValue() === $password->value->getValue();
    }

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

    public function toString(): string
    {
        return $this->value->getValue();
    }
}
