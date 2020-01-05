<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\Path as Model;
use Innmind\BlackBox\Set;

final class Path
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
                return (bool) \preg_match('~\S+~', $value);
            }),
        );
    }
}
