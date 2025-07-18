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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('ユーザ名');
            $table->string('email')->unique()->comment('メールアドレス');
            $table->string('password')->comment('パスワード');
            $table->dateTime('created_at')->useCurrent()->comment('作成日時');
            $table->dateTime('updated_at')->nullable()->useCurrentOnUpdate()->comment('更新日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
