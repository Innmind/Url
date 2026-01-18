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
        return Set::of('http', 'https', 'ftp', 'ssh')->map(Model::of(...));
    }
}
