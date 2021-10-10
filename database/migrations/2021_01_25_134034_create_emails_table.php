<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->text('from_email')->nullable();
            $table->text('from_name')->nullable();
            $table->text('to_email')->nullable();
            $table->text('to_name')->nullable();
            $table->text('reply_to_email')->nullable();
            $table->text('reply_to_name')->nullable();
            $table->text('subject')->nullable();
            $table->jsonb('data')->nullable();
            $table->text('email_type')->nullable();
            $table->bigInteger('sender_id')->nullable();
            $table->bigInteger('receiver_id')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('marked_as_spam_at')->nullable();
            $table->bigInteger('parent_email_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emails');
    }
}
