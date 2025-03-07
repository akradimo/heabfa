<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('اسناد', function (Blueprint $table) {
            $table->id();
            $table->string('شماره_سند')->unique();
            $table->timestamp('تاریخ');
            $table->string('نوع');
            $table->text('شرح')->nullable();
            $table->tinyInteger('وضعیت')->default(0);
            $table->unsignedBigInteger('شرکت_id');
            $table->unsignedBigInteger('ایجاد_کننده_id');
            $table->unsignedBigInteger('تایید_کننده_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('شرکت_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');

            $table->foreign('ایجاد_کننده_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            $table->foreign('تایید_کننده_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('اسناد');
    }
};