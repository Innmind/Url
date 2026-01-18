<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url\Authority\UserInformation;

use Innmind\Url\Authority\UserInformation\User as Model;
use Innmind\BlackBox\Set;

final class User
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set::strings()
            ->filter(static fn($value) => (bool) \preg_match('/^[\pL\pN-]+$/', $value))
            ->map(Model::of(...));
    }
}
