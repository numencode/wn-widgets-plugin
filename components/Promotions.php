<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Components;

use NumenCode\Widgets\Classes\GroupComponent;
use NumenCode\Widgets\Models\PromotionGroup;

/**
 * Displays a promotion group.
 */
class Promotions extends GroupComponent
{
    public static bool $overrideLayout = true;

    protected string $groupModel = PromotionGroup::class;
}
