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
        return self::strings()->map(Model::of(...));
    }

    /**
     * @return Set<Model>
     */
    public static function directories(): Set
    {
        return self::strings()
            ->map(static fn($value) => \rtrim($value, '/').'/')
            ->map(Model::of(...));
    }

    /**
     * @return Set<string>
     */
    private static function strings(): Set
    {
        return Set::strings()->filter(
            static fn($value) => (bool) \preg_match('~\S+~', $value),
        );
    }
}
