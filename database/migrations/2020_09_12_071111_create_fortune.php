<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFortune extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fortune', function (Blueprint $table) {
            $table->unsignedInteger('id', 1)->comment('流水號');
            $table->string('astro', 3)->comment('星座');
            $table->date('execute_day')->comment('執行日期');
            $table->string('fortune', 10)->comment('整體運勢');
            $table->string('fortune_comment')->comment('整體運勢說明');
            $table->string('love', 10)->comment('愛情運勢');
            $table->string('love_comment')->comment('愛情運勢說明');
            $table->string('cause', 10)->comment('事業運勢');
            $table->string('cause_comment')->comment('事業運勢說明');
            $table->string('money', 10)->comment('財運運勢');
            $table->string('money_comment')->comment('財運運勢說明');
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
        Schema::dropIfExists('fortune');
    }
}
