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
            $table->id()->comment('帳號流水號');
            $table->bigInteger('hrm_id')->unique()->comment('HRM員工流水號(從HRM同步)');
            $table->string('user_no')->unique()->comment('HRM員工編號(從HRM同步)');
            $table->string('chinese_name')->comment('中文姓名');
            $table->string('english_name')->comment('英文姓名');
            $table->string('email')->unique()->comment('電子信箱');
            $table->string('phone_number')->nullable()->comment('電話號碼');
            $table->string('mobile_number')->nullable()->comment('手機號碼');
            $table->char('shared_secret', 50)->nullable()->comment('TFA共享密鑰');
            $table->dateTime('last_tfa_verification_at')->nullable()->comment('最近一次驗證時間');
            $table->dateTime('created_at')->nullable()->comment('建立日期');
            $table->string('created_by', 64)->nullable()->comment('建立人員');
            $table->dateTime('updated_at')->nullable()->comment('更新日期');
            $table->string('updated_by', 64)->nullable()->comment('更新人員');
            $table->dateTime('deleted_at')->nullable()->comment('刪除日期');
            $table->tinyInteger('is_valid')->default('0')->comment('帳號有效狀態 (0:無效 | 1:有效)');
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
