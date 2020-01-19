<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatatagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datatags', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger("group_id");
            $table->foreign("group_id")
                ->references("id")
                ->on("datatag_groups")
                ->onDelete("CASCADE");
            $table->string("token");
            $table->integer("scans")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datatags');
    }
}
