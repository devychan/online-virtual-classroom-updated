<?php

use App\Models\Room;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table){
            $table->id();
            $table->text('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('image_path')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('classrooms', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('class_code')->nullable(false);
            $table->text('class_name');
            $table->text('class_section');
            $table->text('class_subject');
            $table->text('class_room');
            $table->timestamps();
        });
        Schema::create('enrolls', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('classroom_id');
            $table->foreign('classroom_id')->references('id')->on('classrooms')->onDelete('cascade');
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('classrooms');
        Schema::dropIfExists('enrolls');
    }
};
