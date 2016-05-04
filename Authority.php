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

    public function __construct(
        UserInformationInterface $userInformation,
        HostInterface $host,
        PortInterface $port
    ) {
        $this->userInformation = $userInformation;
        $this->host = $host;
        $this->port = $port;
    }

    public function userInformation(): UserInformationInterface
    {
        return $this->userInformation;
    }

    public function host(): HostInterface
    {
        return $this->host;
    }

    public function port(): PortInterface
    {
        return $this->port;
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
