<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('left')->unsigned()->notNull();
            $table->integer('right')->unsigned()->notNull();
            $table->integer('level')->unsigned()->notNull();
            $table->text('message');
        });

        DB::table('comments')->insert(
            ['left' => 1, 'right' => 2, 'level' => 0, 'message' => 'root']
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
