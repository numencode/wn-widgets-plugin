<?php

declare(strict_types=1);

namespace NumenCode\Widgets\Updates;

use Schema;
use Winter\Storm\Database\Schema\Blueprint;
use Winter\Storm\Database\Updates\Migration;

/**
 * Counters as a widget type: counter groups with their counters (2.0.0).
 */
class CreateNumencodeWidgetsCountersTables extends Migration
{
    public function up(): void
    {
        Schema::create('numencode_widgets_counters_groups', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title')->nullable();
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('numencode_widgets_counters_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('group_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->string('icon')->nullable();
            $table->string('value')->nullable();
            $table->string('suffix')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('sort_order')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('numencode_widgets_counters_groups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numencode_widgets_counters_items');
        Schema::dropIfExists('numencode_widgets_counters_groups');
    }
}
