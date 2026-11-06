<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Components;

use NumenCode\Widgets\Classes\GroupComponent;
use NumenCode\Widgets\Models\CounterGroup;

/**
 * Displays a group of counters.
 */
class Counters extends GroupComponent
{
    public static bool $overrideLayout = true;

    protected string $groupModel = CounterGroup::class;
}
