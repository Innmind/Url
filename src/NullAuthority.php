<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Authority\{
    UserInformationInterface,
    HostInterface,
    PortInterface,
    NullUserInformation,
    NullHost,
    NullPort
};

final class NullAuthority implements AuthorityInterface
{
    private $info;
    private $host;
    private $port;

    public function __construct()
    {
        $this->info = new NullUserInformation;
        $this->host = new NullHost;
        $this->port = new NullPort;
    }

    public function userInformation(): UserInformationInterface
    {
        return $this->info;
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
        return '';
    }
}
