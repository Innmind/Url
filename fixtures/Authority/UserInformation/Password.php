<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\Password as Model;
use Innmind\BlackBox\Set;

final class Password
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set\Decorate::of(
            static function(string $value): Model {
                return Model::of($value);
            },
            Set\Strings::any()->filter(static function(string $value): bool {
                return (bool) \preg_match('/^[\pL\pN-]+$/', $value);
            }),
        );
    }
}
