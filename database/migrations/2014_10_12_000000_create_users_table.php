<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->text('username')->nullable();
            $table->text('email')->nullable();
            $table->text('password')->nullable();
            $table->text('remember_token')->nullable();
            $table->text('full_name')->nullable();
            $table->text('timezone')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->text('profile_image')->nullable();
            $table->timestamp('newsletter_sent_at')->nullable();
            $table->text('newsletter_frequency')->default('weekly');
            $table->softDeletes();
            $table->boolean('maybe_spam')->default(false);
            $table->jsonb('settings')->nullable();
            $table->boolean('notify_when_available')->default(true);
            $table->text('api_key')->nullable();
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
}
