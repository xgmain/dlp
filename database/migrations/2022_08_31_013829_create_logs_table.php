<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('rule_id')->index()->unsigned();
            $table->string('system')->nullable();
            $table->string('event')->nullable();
            $table->string('severity');
            $table->text('content')->nullable();
            $table->string('token', 255)->nullable();
            $table->string('ip')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('form_id')->nullable();
            $table->string('question_id')->nullable();
            $table->json('sibling')->nullable();
            $table->json('position')->nullable();
            $table->boolean('available')->default(true);
            $table->bigInteger('src')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('rule_id')->references('id')->on('rules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
