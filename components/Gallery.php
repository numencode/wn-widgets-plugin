<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Components;

use NumenCode\Widgets\Classes\GroupComponent;
use NumenCode\Widgets\Models\GalleryGroup;

/**
 * Displays a gallery group as a grid of pictures.
 */
class Gallery extends GroupComponent
{
    /**
     * Allowed numbers of items per row (they divide a 12-column grid evenly).
     */
    public const ITEMS_PER_ROW = [1, 2, 3, 4, 6, 12];

    public static bool $overrideLayout = true;

    /**
     * Number of items per row, one of ITEMS_PER_ROW.
     */
    public int $itemsPerRow = 4;

    protected string $groupModel = GalleryGroup::class;

    public function defineProperties(): array
    {
        return parent::defineProperties() + [
            'items_per_row' => [
                'title'   => 'numencode.widgets::lang.gallery.items_per_row',
                'type'    => 'dropdown',
                'options' => array_combine(
                    array_map('strval', self::ITEMS_PER_ROW),
                    array_map('strval', self::ITEMS_PER_ROW)
                ),
                'default' => '4',
            ],
        ];
    }

    public function onRun()
    {
        parent::onRun();

        $this->itemsPerRow = static::normalizeItemsPerRow($this->property('items_per_row'));
    }

    /**
     * Width of one item in a 12-column grid (e.g. 3 for 4 items per row).
     */
    public function columnWidth(): int
    {
        return intdiv(12, $this->itemsPerRow);
    }

    /**
     * Returns the largest allowed value not above the given one (at least 1); 4 when it isn't a number.
     */
    public static function normalizeItemsPerRow(mixed $value): int
    {
        if (!is_numeric($value)) {
            return 4;
        }

        $value = (int) $value;
        $allowed = array_filter(self::ITEMS_PER_ROW, fn (int $option) => $option <= $value);

        return $allowed ? max($allowed) : 1;
    }
}
