<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول العملاء: العميل قد يكون شخصاً أو شركة.
 * له بيانات تواصل وموقع على الخريطة وأرقام رسمية.
 * تحته تندرج المباني (buildings).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            // نوع العميل: شخص أو شركة
            $table->enum('type', ['individual', 'company'])->default('company');

            $table->string('name');              // اسم الشخص أو الشركة
            $table->string('phone');             // رقم الجوال
            $table->string('email')->nullable();

            // الموقع على الخريطة
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable(); // عنوان نصي

            // الأرقام الرسمية
            $table->string('tax_number')->nullable();      // الرقم الضريبي
            $table->string('national_id')->nullable();     // رقم الهوية / السجل التجاري

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
