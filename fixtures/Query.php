<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\Query as Model;
use Innmind\BlackBox\Set;

final class Query
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set::strings()
            ->filter(static fn($value) => (bool) \preg_match('/^\S+$/', $value))
            ->map(Model::of(...));
    }
}
