<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('max_bot_updates', static function (Blueprint $table) {
            $table->id();
            $table->string('update_type');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('chat_id')->nullable();
            $table->string('chat_type')->nullable();
            $table->json('data');
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('max_bot_user_updates');
    }
};
