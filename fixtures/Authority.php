<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\Authority as Model;
use Innmind\BlackBox\Set;

final class Authority
{
    /**
     * @return Set\Provider<Model>
     */
    public static function any(): Set\Provider
    {
        return Set::compose(
            Model::of(...),
            Authority\UserInformation::any(),
            Set::either(
                Authority\Host::any(),
                Set::of(Model\Host::none()),
            ),
            Set::either(
                Authority\Port::any(),
                Set::of(Model\Port::none()),
            ),
        );
    }
}
