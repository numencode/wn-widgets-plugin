<?php namespace NumenCode\Widgets\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateNumencodeWidgetsFeaturesGroupsTable extends Migration
{
    public function up()
    {
        Schema::create('numencode_widgets_features_groups', function ($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title', 255)->nullable();
            $table->text('content')->nullable();
            $table->string('link')->nullable();
            $table->string('link_title')->nullable();
            $table->boolean('is_published');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('numencode_widgets_features_groups');
    }
}
