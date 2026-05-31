<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول المصاعد: كل مبنى يحتوي مصعداً أو أكثر.
 * مثال: 5 مصاعد نوع ميتسوبيشي داخل فندق واحة المسك.
 * طلبات الصيانة ترتبط بمصعد محدد (وليس بالمبنى عموماً).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('elevators', function (Blueprint $table) {
            $table->id();

            $table->foreignId('building_id')
                  ->constrained('buildings')
                  ->cascadeOnDelete();

            $table->string('label')->nullable();        // تسمية داخلية مثل: مصعد رقم 1 / المصعد الرئيسي
            $table->string('brand')->nullable();        // النوع/الماركة مثل: Mitsubishi
            $table->string('model')->nullable();        // الموديل
            $table->string('serial_number')->nullable();// الرقم التسلسلي
            $table->unsignedSmallInteger('capacity_kg')->nullable();  // الحمولة كجم
            $table->unsignedSmallInteger('floors')->nullable();       // عدد الطوابق
            $table->date('installation_date')->nullable();

            // الحالة التشغيلية للمصعد
            $table->enum('status', ['operational', 'out_of_service', 'under_maintenance'])
                  ->default('operational');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elevators');
    }
};
