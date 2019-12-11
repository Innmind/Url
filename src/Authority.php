<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Authority\{
    UserInformationInterface,
    Host,
    Port,
    NullPort,
    UserInformation\NullUser
};

final class Authority
{
    private $userInformation;
    private $host;
    private $port;

    private function __construct(
        UserInformationInterface $userInformation,
        Host $host,
        Port $port
    ) {
        $this->userInformation = $userInformation;
        $this->host = $host;
        $this->port = $port;
    }

    public static function of(
        UserInformationInterface $userInformation,
        Host $host,
        Port $port
    ): self {
        return new self($userInformation, $host, $port);
    }

    public static function null(): self
    {
        return new self(
            Authority\UserInformation::null(),
            Authority\Host::null(),
            Authority\Port::null(),
        );
    }

    public function userInformation(): UserInformationInterface
    {
        return $this->userInformation;
    }

    public function withUserInformation(UserInformationInterface $info): self
    {
        return new self($info, $this->host, $this->port);
    }

    public function host(): Host
    {
        return $this->host;
    }

    public function withHost(Host $host): self
    {
        return new self($this->userInformation, $host, $this->port);
    }

    public function port(): Port
    {
        return $this->port;
    }

    public function withPort(Port $port): self
    {
        return new self($this->userInformation, $this->host, $port);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s',
            $this->userInformation,
            !$this->userInformation->user() instanceof NullUser ? '@'.(string) $this->host : $this->host,
            $this->port->format(),
        );
    }
}
