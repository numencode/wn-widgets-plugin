<?php namespace NumenCode\Widgets\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class CreateNumencodeWidgetsHighlightsItemsTable extends Migration
{
    public function up()
    {
        Schema::create('numencode_widgets_highlights_items', function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('group_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->string('picture')->nullable();
            $table->boolean('is_published');
            $table->integer('sort_order')->nullable();
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('numencode_widgets_highlights_groups')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('numencode_widgets_highlights_items');
    }
}
