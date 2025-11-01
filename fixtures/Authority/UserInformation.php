<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url\Authority;

use Innmind\Url\Authority\UserInformation as Model;
use Innmind\BlackBox\Set;

final class UserInformation
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set::either(
            Set::compose(
                Model::of(...),
                UserInformation\User::any(),
                Set::either(
                    UserInformation\Password::any(),
                    Set::of(Model\Password::none()),
                ),
            ),
            Set::of(Model::none()),
        );
    }
}
