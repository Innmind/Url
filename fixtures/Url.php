<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\{
    Url as Model,
    Authority as AuthorityModel,
    Path as PathModel,
    Query as QueryModel,
    Fragment as FragmentModel,
};
use Innmind\BlackBox\Set;

final class Url
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set\Composite::of(
            static function($scheme, $authority, $path, $query, $fragment): Model {
                return new Model($scheme, $authority, $path, $query, $fragment);
            },
            Scheme::any(),
            new Set\Either(
                Authority::any(),
                Set\Elements::of(AuthorityModel::none()),
            ),
            new Set\Either(
                Path::any(),
                Set\Elements::of(PathModel::none()),
            ),
            new Set\Either(
                Query::any(),
                Set\Elements::of(QueryModel::none()),
            ),
            new Set\Either(
                Fragment::any(),
                Set\Elements::of(FragmentModel::none()),
            ),
        );
    }
}
