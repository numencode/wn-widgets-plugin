<?php namespace NumenCode\Widgets\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTableNumencodeWidgetsPromotionsGroups extends Migration
{
    public function up()
    {
        Schema::create('numencode_widgets_promotions_groups', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title', 255)->nullable();
            $table->boolean('is_published');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('numencode_widgets_promotions_groups');
    }
}
