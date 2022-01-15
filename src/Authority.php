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
    private UserInformation $userInformation;
    private Host $host;
    private Port $port;

    private function __construct(
        UserInformation $userInformation,
        Host $host,
        Port $port,
    ) {
        $this->userInformation = $userInformation;
        $this->host = $host;
        $this->port = $port;
    }

    /**
     * @psalm-pure
     */
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
    public static function none(): self
    {
        return new self(
            Authority\UserInformation::none(),
            Authority\Host::none(),
            Authority\Port::none(),
        );
    }

    public function equals(self $authority): bool
    {
        return $this->userInformation->equals($authority->userInformation()) &&
            $this->host->equals($authority->host()) &&
            $this->port->equals($authority->port());
    }

    public function userInformation(): UserInformation
    {
        return $this->userInformation;
    }

    public function withUserInformation(UserInformation $info): self
    {
        return new self($info, $this->host, $this->port);
    }

    public function withoutUserInformation(): self
    {
        return new self(UserInformation::none(), $this->host, $this->port);
    }

    public function host(): Host
    {
        return $this->host;
    }

    public function withHost(Host $host): self
    {
        return new self($this->userInformation, $host, $this->port);
    }

    public function withoutHost(): self
    {
        return new self($this->userInformation, Host::none(), $this->port);
    }

    public function port(): Port
    {
        return $this->port;
    }

    public function withPort(Port $port): self
    {
        return new self($this->userInformation, $this->host, $port);
    }

    public function withoutPort(): self
    {
        return new self($this->userInformation, $this->host, Port::none());
    }

    public function toString(): string
    {
        return $this->userInformation->format($this->host).$this->port->format();
    }
}
