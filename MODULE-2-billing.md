<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول المستخدمين: يشمل جميع من يسجّل دخوله للنظام
 * (المدير، المشرفون، الفنيون، وحسابات العملاء).
 * يتم التمييز بينهم بحقل role.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // الدور: admin | supervisor | technician | customer
            $table->enum('role', ['admin', 'supervisor', 'technician', 'customer'])
                  ->default('customer')
                  ->index();

            // للفنيين: تخصص / حالة التوفر
            $table->boolean('is_active')->default(true);

            // ربط حساب العميل بسجل العميل في جدول customers (يكون null لغير العملاء)
            $table->foreignId('customer_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
