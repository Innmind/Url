<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\Scheme as Model;
use Innmind\BlackBox\Set;

final class Scheme
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set\Decorate::of(
            static function(string $value): Model {
                return Model::of($value);
            },
            Set\Elements::of('http', 'https', 'ftp', 'ssh'),
        );
    }
}
