<?php
declare(strict_types = 1);

namespace Fixtures\Innmind\Url\Authority;

use Innmind\Url\Authority\Host as Model;
use Innmind\BlackBox\Set;

final class Host
{
    /**
     * @return Set<Model>
     */
    public static function any(): Set
    {
        $chars = Set::strings()->chars()->alphanumerical();
        $bounds = Set::strings()
            ->madeOf($chars)
            ->atLeast(1);

        $strings = Set::either(
            $bounds,
            Set::compose(
                static fn(string ...$parts) => \implode('', $parts),
                $bounds,
                Set::strings()->madeOf(
                    $chars,
                    Set::of('-', '.', '_'),
                    Set::either(
                        Set::strings()->unicode()->cyrillic(),
                        Set::strings()->unicode()->cyrillicSupplement(),
                        Set::strings()->unicode()->arabicSupplement(),
                        Set::integers()
                            ->between(0x3041, 0x3094) // hiragana letters
                            ->map(\IntlChar::chr(...))
                            ->filter(\is_string(...)),
                        Set::integers()
                            ->between(0x30A1, 0x30FA) // katakana letters
                            ->map(\IntlChar::chr(...))
                            ->filter(\is_string(...)),
                        Set::integers()
                            ->between(0x00DF, 0x00F6) // special latin letters
                            ->map(\IntlChar::chr(...))
                            ->filter(\is_string(...)),
                        Set::integers()
                            ->between(0x00F6, 0x00FF) // special latin letters
                            ->map(\IntlChar::chr(...))
                            ->filter(\is_string(...)),
                        // Set::strings()->unicode()->katakana(),
                        // Set::strings()->unicode()->latin1Supplement(),
                        // Set::strings()->unicode()->latinExtendedA(),
                        // Set::strings()->unicode()->latinExtendedB(),
                    )->filter(static fn($char) => \IntlChar::ord($char) !== null),
                ),
                $bounds,
            ),
        );

        return $strings
            ->filter(static fn($value) => (bool) \preg_match('~^\S+$~ix', $value))
            ->exclude(static fn($value) => \str_contains($value, '..'))
            ->map(Model::of(...));
    }
}
