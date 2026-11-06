<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Components;

use NumenCode\Widgets\Classes\GroupComponent;
use NumenCode\Widgets\Models\FeatureGroup;

/**
 * Displays a feature group.
 */
class Features extends GroupComponent
{
    public static bool $overrideLayout = true;

    protected string $groupModel = FeatureGroup::class;
}
