<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Authority\{
    UserInformationInterface,
    HostInterface,
    PortInterface,
    NullPort,
    UserInformation\NullUser
};

final class Authority implements AuthorityInterface
{
    private $userInformation;
    private $host;
    private $port;

    private function __construct(
        UserInformationInterface $userInformation,
        HostInterface $host,
        PortInterface $port
    ) {
        $this->userInformation = $userInformation;
        $this->host = $host;
        $this->port = $port;
    }

    public static function of(
        UserInformationInterface $userInformation,
        HostInterface $host,
        PortInterface $port
    ): self {
        return new self($userInformation, $host, $port);
    }

    public static function null(): AuthorityInterface
    {
        return new NullAuthority;
    }

    public function userInformation(): UserInformationInterface
    {
        return $this->userInformation;
    }

    public function withUserInformation(UserInformationInterface $info): AuthorityInterface
    {
        return new self($info, $this->host, $this->port);
    }

    public function host(): HostInterface
    {
        return $this->host;
    }

    public function withHost(HostInterface $host): AuthorityInterface
    {
        return new self($this->userInformation, $host, $this->port);
    }

    public function port(): PortInterface
    {
        return $this->port;
    }

    public function withPort(PortInterface $port): AuthorityInterface
    {
        return new self($this->userInformation, $this->host, $port);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s%s%s',
            $this->userInformation,
            !$this->userInformation->user() instanceof NullUser ? '@'.(string) $this->host : $this->host,
            !$this->port instanceof NullPort ? ':'.(string) $this->port : ''
        );
    }
}
