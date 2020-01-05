<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url\Authority;

use Innmind\Url\Authority\Port as Model;
use Innmind\BlackBox\Set;

final class Port
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set\Decorate::immutable(
            static function(int $value): Model {
                return Model::of($value);
            },
            Set\Integers::above(0),
        );
    }
}
