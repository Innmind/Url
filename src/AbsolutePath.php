<?php
declare(strict_types = 1);

namespace Innmind\Url;

/**
 * @psalm-immutable
 */
final class AbsolutePath extends Path
{
    #[\Override]
    public function absolute(): bool
    {
        return true;
    }
}
