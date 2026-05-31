<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول المباني: كل عميل يملك مبنى أو أكثر.
 * مثال: "فندق واحة المسك". لكل مبنى مسؤول وموقع.
 * تحته تندرج المصاعد (elevators).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();

            $table->string('name');                 // اسم المبنى مثل: فندق واحة المسك

            // مسؤول المبنى
            $table->string('manager_name')->nullable();
            $table->string('manager_phone')->nullable();

            // الموقع على الخريطة
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buildings');
    }
};
