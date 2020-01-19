<?php


use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatatagGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datatag_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("groupable_type");
            $table->string("groupable_id");
            $table->string("name");
            $table->string("directory");
            $table->integer("available_scans")->default(1);
            $table->date("expiration_date");
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
        Schema::dropIfExists('datatag_groups');
    }
}
