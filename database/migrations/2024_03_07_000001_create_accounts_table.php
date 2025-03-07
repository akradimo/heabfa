<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('کد')->unique();
            $table->string('عنوان');
            $table->string('نوع_حساب');
            $table->unsignedBigInteger('حساب_والد')->nullable();
            $table->text('توضیحات')->nullable();
            $table->boolean('فعال')->default(true);
            $table->decimal('مانده', 20, 2)->default(0);
            $table->unsignedBigInteger('شرکت_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('حساب_والد')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('restrict');
                  
            $table->foreign('شرکت_id')
                  ->references('id')
                  ->on('companies')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('accounts');
    }
};