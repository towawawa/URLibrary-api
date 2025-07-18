<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('genres', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->string('name')->comment('ジャンル名');
            $table->string('image_path')->nullable()->comment('画像パス');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('genres');
    }
};
