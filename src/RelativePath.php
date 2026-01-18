<?php
declare(strict_types = 1);

namespace Innmind\Url;

/**
 * @psalm-immutable
 */
final class RelativePath extends Path
{
    #[\Override]
    #[\NoDiscard]
    public function absolute(): bool
    {
        return false;
    }
}
