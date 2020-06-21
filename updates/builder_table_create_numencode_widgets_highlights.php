<?php namespace NumenCode\Widgets\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateNumencodeWidgetsHighlights extends Migration
{
    public function up()
    {
        Schema::create('numencode_widgets_highlights', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('group_id')->index()->nullable();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->text('content')->nullable();
            $table->boolean('is_published');
            $table->integer('sort_order');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('numencode_widgets_highlights');
    }
}