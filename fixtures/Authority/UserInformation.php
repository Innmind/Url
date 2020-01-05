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
        return new Set\Either(
            Set\Composite::immutable(
                static function($user, $password): Model {
                    return Model::of($user, $password);
                },
                UserInformation\User::any(),
                new Set\Either(
                    UserInformation\Password::any(),
                    Set\Elements::of(Model\Password::none()),
                ),
            ),
            Set\Elements::of(Model::none()),
        );
    }
}
