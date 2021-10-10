<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_confirmations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('type');
            $table->text('to_email');
            $table->text('to_name')->nullable();
            $table->text('from_name')->nullable();
            $table->text('reply_to_email')->nullable();
            $table->text('webhook_url')->nullable();
            $table->jsonb('data')->nullable();
            $table->timestamp('confirmed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('email_confirmations');
    }
}
