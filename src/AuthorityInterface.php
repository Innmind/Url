<?php
declare(strict_types = 1);

namespace Innmind\Url;

use Innmind\Url\Authority\{
    UserInformationInterface,
    HostInterface,
    PortInterface
};

interface AuthorityInterface
{
    public function userInformation(): UserInformationInterface;
    public function withUserInformation(UserInformationInterface $info): self;
    public function host(): HostInterface;
    public function withHost(HostInterface $host): self;
    public function port(): PortInterface;
    public function withPort(PortInterface $port): self;
    public function __toString(): string;
}
