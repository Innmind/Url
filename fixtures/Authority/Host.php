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
        return Set::strings()
            ->filter(static fn($value) => (bool) \preg_match('~^\S+$~ix', $value))
            ->map(Model::of(...));
    }
}
