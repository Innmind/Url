<?php
declare(strict_types = 1);

namespace Innmind\Url;

final class AbsolutePath extends Path
{
    public function absolute(): bool
    {
        return true;
    }
}
