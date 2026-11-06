<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Components;

use NumenCode\Widgets\Classes\GroupComponent;
use NumenCode\Widgets\Models\HighlightGroup;

/**
 * Displays a highlight group.
 */
class Highlights extends GroupComponent
{
    public static bool $overrideLayout = true;

    protected string $groupModel = HighlightGroup::class;
}
