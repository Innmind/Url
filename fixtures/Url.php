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
        return Set\Composite::immutable(
            static function($scheme, $authority, $path, $query, $fragment): Model {
                return new Model($scheme, $authority, $path, $query, $fragment);
            },
            Scheme::any(),
            Set\Either::any(
                Authority::any(),
                Set\Elements::of(AuthorityModel::none()),
            ),
            Set\Either::any(
                Path::any(),
                Set\Elements::of(PathModel::none()),
            ),
            Set\Either::any(
                Query::any(),
                Set\Elements::of(QueryModel::none()),
            ),
            Set\Either::any(
                Fragment::any(),
                Set\Elements::of(FragmentModel::none()),
            ),
        );
    }
}
