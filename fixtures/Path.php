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
            self::strings(),
        );
    }

    /**
     * @return Set<Model>
     */
    public static function directories(): Set
    {
        return Set\Decorate::immutable(
            static function(string $value): Model {
                return Model::of(\rtrim($value, '/').'/');
            },
            self::strings()
        );
    }

    /**
     * @return Set<string>
     */
    private static function strings(): Set
    {
        return Set\Strings::any()->filter(static function(string $value): bool {
            return (bool) \preg_match('~\S+~', $value);
        });
    }
}
