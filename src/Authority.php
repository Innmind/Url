<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Authority\{
    UserInformation,
    Host,
    Port,
};

/**
 * @psalm-immutable
 */
final class Authority
{
    private function __construct(
        private UserInformation $userInformation,
        private Host $host,
        private Port $port,
    ) {
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(
        UserInformation $userInformation,
        Host $host,
        Port $port,
    ): self {
        return new self($userInformation, $host, $port);
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function none(): self
    {
        return new self(
            UserInformation::none(),
            Host::none(),
            Port::none(),
        );
    }

    #[\NoDiscard]
    public function equals(self $authority): bool
    {
        return $this->userInformation->equals($authority->userInformation()) &&
            $this->host->equals($authority->host()) &&
            $this->port->equals($authority->port());
    }

    #[\NoDiscard]
    public function userInformation(): UserInformation
    {
        return $this->userInformation;
    }

    #[\NoDiscard]
    public function withUserInformation(UserInformation $info): self
    {
        return new self($info, $this->host, $this->port);
    }

    #[\NoDiscard]
    public function withoutUserInformation(): self
    {
        return new self(UserInformation::none(), $this->host, $this->port);
    }

    #[\NoDiscard]
    public function host(): Host
    {
        return $this->host;
    }

    #[\NoDiscard]
    public function withHost(Host $host): self
    {
        return new self($this->userInformation, $host, $this->port);
    }

    #[\NoDiscard]
    public function withoutHost(): self
    {
        return new self($this->userInformation, Host::none(), $this->port);
    }

    #[\NoDiscard]
    public function port(): Port
    {
        return $this->port;
    }

    #[\NoDiscard]
    public function withPort(Port $port): self
    {
        return new self($this->userInformation, $this->host, $port);
    }

    #[\NoDiscard]
    public function withoutPort(): self
    {
        return new self($this->userInformation, $this->host, Port::none());
    }

    #[\NoDiscard]
    public function toString(): string
    {
        return $this->userInformation->format($this->host).$this->port->format();
    }
}
