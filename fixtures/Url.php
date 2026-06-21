<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\{
    Url as Model,
    Scheme as SchemeModel,
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
        $url = Set::compose(
            Model::from(...),
            Set::either(
                Scheme::any(),
                Set::of(
                    SchemeModel::none(),
                    SchemeModel::less(),
                ),
            ),
            Set::either(
                Authority::any(),
                Set::of(AuthorityModel::none()),
            ),
            Set::either(
                Path::absolute(),
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
        )
            ->exclude(
                static fn($url) => !$url->authority()->port()->equals(AuthorityModel\Port::none()) && (
                    $url->authority()->host()->equals(AuthorityModel\Host::none()) ||
                    $url->scheme()->equals(SchemeModel::none())
                ),
            )
            ->exclude(
                static fn($url) => !$url->query()->equals(QueryModel::none()) &&
                    $url->path()->equals(PathModel::none()),
            )
            ->exclude(
                static fn($url) => !$url->fragment()->equals(FragmentModel::none()) &&
                    $url->path()->equals(PathModel::none()),
            )
            ->exclude(
                static fn($url) => $url->scheme()->equals(SchemeModel::less()) &&
                    $url->authority()->equals(AuthorityModel::none()),
            )
            ->exclude(
                static fn($url) => $url->authority()->equals(AuthorityModel::none()) && !(
                    $url->scheme()->equals(SchemeModel::none()) ||
                    $url->scheme()->equals(SchemeModel::less())
                ),
            )
            ->exclude(
                static fn($url) => !$url->scheme()->equals(SchemeModel::none()) &&
                    $url->authority()->port()->value() > 65535,
            );
        $path = Path::relative()->map(
            static fn($path) => Model::from(
                SchemeModel::none(),
                AuthorityModel::none(),
                $path,
                QueryModel::none(),
                FragmentModel::none(),
            ),
        );
        $file = Path::absolute()->map(
            static fn($path) => Model::from(
                SchemeModel::of('file'),
                AuthorityModel::none(),
                $path,
                QueryModel::none(),
                FragmentModel::none(),
            ),
        );

        return Set::either($url, $path, $file);
    }
}
