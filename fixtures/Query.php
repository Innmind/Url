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
            ->madeOf(
                Set::either(
                    Set::strings()->chars()->ascii()->exclude(
                        static fn($char) => $char === '#',
                    ),
                    Set::strings()->unicode()->basicLatin(),
                    Set::strings()->unicode()->cyrillic(),
                    Set::strings()->unicode()->arabic(),
                )->map(\rawurlencode(...)),
                Set::of(' '),
            )
            ->map(Model::of(...));
    }
}
