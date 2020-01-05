<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url\Authority;

use Innmind\Url\Authority\Host as Model;
use Innmind\BlackBox\Set;

final class Host
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set\Decorate::immutable(
            static function(string $value): Model {
                return Model::of($value);
            },
            Set\Strings::any()->filter(static function(string $value): bool {
                return (bool) \preg_match('~^\S+$~ix', $value);
            }),
        );
    }
}
