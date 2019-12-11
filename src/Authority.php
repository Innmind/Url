<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Authority\{
    UserInformation,
    Host,
    Port,
};

final class Authority
{
    private $userInformation;
    private $host;
    private $port;

    private function __construct(
        UserInformation $userInformation,
        Host $host,
        Port $port
    ) {
        $this->userInformation = $userInformation;
        $this->host = $host;
        $this->port = $port;
    }

    public static function of(
        UserInformation $userInformation,
        Host $host,
        Port $port
    ): self {
        return new self($userInformation, $host, $port);
    }

    public static function none(): self
    {
        return new self(
            Authority\UserInformation::none(),
            Authority\Host::none(),
            Authority\Port::none(),
        );
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

    public function __toString(): string
    {
        return $this->userInformation->format($this->host).$this->port->format();
    }
}
