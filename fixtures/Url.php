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
     * @return Set\Provider<Model>
     */
    public static function any(): Set\Provider
    {
        return Set::compose(
            Model::from(...),
            Scheme::any(),
            Set::either(
                Authority::any(),
                Set::of(AuthorityModel::none()),
            ),
            Set::either(
                Path::any(),
                Set::of(PathModel::none()),
            ),
            Set::either(
                Query::any(),
                Set::of(QueryModel::none()),
            ),
            Set::either(
                Fragment::any(),
                Set::of(FragmentModel::none()),
            ),
        );
    }
}
