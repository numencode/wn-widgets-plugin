<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Updates;

use Db;
use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

/**
 * 2.0.0: widget groups no longer use soft deletes, and new records are published by default.
 *
 * Groups that were soft-deleted in 1.x could not be restored from the backend; they and their
 * items are deleted permanently before the deleted_at column is dropped.
 */
class V200RemoveSoftDeletes extends Migration
{
    protected const TYPES = ['promotions', 'features', 'highlights', 'gallery'];

    public function up(): void
    {
        foreach (self::TYPES as $type) {
            $groups = 'numencode_widgets_' . $type . '_groups';
            $items = 'numencode_widgets_' . $type . '_items';

            if (Schema::hasColumn($groups, 'deleted_at')) {
                $trashed = Db::table($groups)->whereNotNull('deleted_at')->pluck('id');

                if ($trashed->isNotEmpty()) {
                    // Explicitly, in case the database doesn't enforce the foreign key cascade
                    Db::table($items)->whereIn('group_id', $trashed)->delete();
                    Db::table($groups)->whereIn('id', $trashed)->delete();
                }

                Schema::table($groups, function (Blueprint $table) {
                    $table->dropSoftDeletes();
                });
            }

            foreach ([$groups, $items] as $tableName) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->boolean('is_published')->default(true)->change();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (self::TYPES as $type) {
            Schema::table('numencode_widgets_' . $type . '_groups', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }
}
