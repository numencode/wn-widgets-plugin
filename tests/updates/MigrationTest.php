<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Tests\Updates;

use Db;
use NumenCode\Widgets\Tests\WidgetsTestCase;
use NumenCode\Widgets\Updates\V200RemoveSoftDeletes;
use Schema;

require_once __DIR__ . '/../../updates/v2_0_0_remove_soft_deletes.php';

class MigrationTest extends WidgetsTestCase
{
    protected const TABLES = ['promotions', 'features', 'highlights', 'gallery', 'counters'];

    public function testSchemaOf200(): void
    {
        foreach (self::TABLES as $type) {
            $this->assertTrue(Schema::hasTable('numencode_widgets_' . $type . '_groups'), $type);
            $this->assertTrue(Schema::hasTable('numencode_widgets_' . $type . '_items'), $type);
            $this->assertFalse(Schema::hasColumn('numencode_widgets_' . $type . '_groups', 'deleted_at'), $type);
        }

        $this->assertTrue(Schema::hasColumns('numencode_widgets_counters_items', ['group_id', 'title', 'icon', 'value', 'suffix', 'sort_order']));
    }

    public function testSoftDeletedGroupsAreDeletedPermanently(): void
    {
        $migration = new V200RemoveSoftDeletes();
        $migration->down();

        $groups = 'numencode_widgets_gallery_groups';
        $items = 'numencode_widgets_gallery_items';
        $trashed = Db::table($groups)->insertGetId(['title' => 'Trashed', 'deleted_at' => now()]);
        $kept = Db::table($groups)->insertGetId(['title' => 'Kept']);
        Db::table($items)->insert([['group_id' => $trashed, 'title' => 'Trashed item'], ['group_id' => $kept, 'title' => 'Kept item']]);

        $migration->up();

        $this->assertSame(['Kept'], Db::table($groups)->pluck('title')->all());
        $this->assertSame(['Kept item'], Db::table($items)->pluck('title')->all());
        $this->assertFalse(Schema::hasColumn($groups, 'deleted_at'));
    }
}
