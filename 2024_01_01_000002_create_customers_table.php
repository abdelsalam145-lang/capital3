<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول العقود: عقد صيانة يخص عميلاً ويغطّي مصاعد محددة.
 * يحدّد دورة الفوترة (سنوي/نصف سنوي/ربع سنوي/شهري) فتُولَّد الفواتير منه.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            // رقم العقد المقروء مثل: CON-2024-0001
            $table->string('reference')->unique();

            $table->foreignId('customer_id')
                  ->constrained('customers')
                  ->cascadeOnDelete();

            // نوع العقد
            $table->enum('type', ['full_maintenance', 'preventive', 'on_call', 'warranty'])
                  ->default('preventive');

            // القيمة الإجمالية للعقد (قبل الضريبة) ودورة الفوترة
            $table->decimal('value', 12, 2);              // قيمة الدورة الواحدة
            $table->enum('billing_cycle', ['monthly', 'quarterly', 'semi_annual', 'annual'])
                  ->default('annual');

            // عدد الزيارات الدورية المشمولة في الدورة (للصيانة الوقائية)
            $table->unsignedSmallInteger('visits_per_cycle')->nullable();

            $table->date('start_date');
            $table->date('end_date');

            // الحالة
            $table->enum('status', ['draft', 'active', 'expired', 'cancelled', 'suspended'])
                  ->default('draft')
                  ->index();

            // التجديد التلقائي
            $table->boolean('auto_renew')->default(false);

            $table->text('terms')->nullable();    // بنود/ملاحظات العقد
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        // المصاعد المشمولة في العقد (عقد يغطّي عدة مصاعد)
        Schema::create('contract_elevator', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained('contracts')->cascadeOnDelete();
            $table->foreignId('elevator_id')->constrained('elevators')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['contract_id', 'elevator_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contract_elevator');
        Schema::dropIfExists('contracts');
    }
};
