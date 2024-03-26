<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('username',50)->unique();
            $table->string('excerpt',20)->nullable();
            $table->string('body',255)->nullable();
            $table->decimal('price')->nullable();
            $table->string('email',50)->unique();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('permission_id')->nullable();
            $table->foreignId('role_id');
            $table->string('password');
            $table->bigInteger('idcardnumber')->nullable();
            $table->bigInteger('norekening')->nullable();
            $table->string('idcardstatcode',5);
            $table->integer('points')->nullable();
            $table->boolean('ban_status')->nullable()->default(false);
            $table->integer('report_times')->nullable();
            $table->integer('unban_times')->nullable();
            $table->decimal('rating_avg', 4, 2)->nullable();
            $table->rememberToken();
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
    }
};
