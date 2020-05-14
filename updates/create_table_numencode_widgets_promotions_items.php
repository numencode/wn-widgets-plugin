<?php namespace NumenCode\Widgets\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableNumencodeWidgetsPromotionsItems extends Migration
{
    public function up()
    {
        Schema::create('numencode_widgets_promotions_items', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('group_id')->unsigned()->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->string('picture')->nullable();
            $table->boolean('is_primary');
            $table->integer('sort_order');
            $table->timestamps();

            $table->foreign('group_id')->references('id')->on('numencode_widgets_promotions_groups')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('numencode_widgets_promotions_items');
    }
}
