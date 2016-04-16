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
    public function host(): HostInterface;
    public function port(): PortInterface;
}
