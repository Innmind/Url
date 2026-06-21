<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url;

use Innmind\Url\{
    Path as Model,
    Url,
};
use Innmind\BlackBox\Set;

final class Path
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        return Set::either(
            self::relative(),
            self::absolute(),
        );
    }

    /**
     * @return Set<Model>
     */
    public static function relative(): Set
    {
        return self::build(
            self::strings()
                ->map(static fn($value) => \ltrim($value, '/'))
                ->exclude(static fn($value) => \str_ends_with($value, '\\')),
        );
    }

    /**
     * @return Set<Model>
     */
    public static function absolute(): Set
    {
        return self::build(
            self::strings()
                ->map(static fn($value) => '/'.\ltrim($value, '/'))
                ->exclude(static fn($value) => \str_ends_with($value, '\\')),
        );
    }

    /**
     * @return Set<Model>
     */
    public static function directories(): Set
    {
        return self::build(
            self::strings()
                ->map(static fn($value) => \rtrim($value, '/').'/'),
        );
    }

    /**
     * @return Set<string>
     */
    private static function strings(): Set
    {
        return Set::strings()
            ->madeOf(
                Set::strings()
                    ->chars()
                    ->ascii()
                    ->exclude(static fn($char) => $char === '?')
                    ->exclude(static fn($char) => $char === '#'),
                Set::either(
                    Set::strings()->unicode()->basicLatin(),
                    Set::strings()->unicode()->cyrillic(),
                    Set::strings()->unicode()->arabic(),
                )->map(\rawurlencode(...)),
            )
            ->filter(static fn($value) => (bool) \preg_match('~\S+~', $value))
            ->exclude(static fn($value) => \str_contains($value, '//'))
            ->exclude(static fn($value) => \str_contains($value, '\\@'))
            ->exclude(static fn($value) => \str_starts_with($value, '\\'))
            ->map(static fn($value) => \trim($value, ' '));
    }

    /**
     * @param Set<string> $strings
     *
     * @return Set<Model>
     */
    private static function build(Set $strings): Set
    {
        return $strings
            ->exclude(static fn($value) => $value === '')
            ->filter(static fn($value) => Url::attempt($value)->match(
                static fn() => true,
                static fn() => false,
            ))
            ->map(Model::of(...));
    }
}
