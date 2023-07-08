<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\Authority as Model;
use Innmind\BlackBox\Set;

final class Authority
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set\Composite::immutable(
            static function($userInfo, $host, $port): Model {
                return Model::of($userInfo, $host, $port);
            },
            Authority\UserInformation::any(),
            Set\Either::any(
                Authority\Host::any(),
                Set\Elements::of(Model\Host::none()),
            ),
            Set\Either::any(
                Authority\Port::any(),
                Set\Elements::of(Model\Port::none()),
            ),
        );
    }
}
