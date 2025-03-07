<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->timestamp('تاریخ');
            $table->unsignedBigInteger('حساب_id');
            $table->unsignedBigInteger('سند_id');
            $table->decimal('بدهکار', 20, 2)->default(0);
            $table->decimal('بستانکار', 20, 2)->default(0);
            $table->string('شرح')->nullable();
            $table->string('شماره_ارجاع')->nullable();
            $table->timestamps();

            $table->foreign('حساب_id')
                  ->references('id')
                  ->on('accounts')
                  ->onDelete('restrict');
                  
            $table->foreign('سند_id')
                  ->references('id')
                  ->on('documents')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};