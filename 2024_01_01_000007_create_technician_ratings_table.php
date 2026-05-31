<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول الطلبات (قلب النظام):
 * يشمل ثلاثة أنواع: صيانة / عطل / معاينة.
 *
 * مصادر الطلب:
 *  - العميل يرفع الطلب بنفسه من بوابته.
 *  - المدير أو أحد المشرفين الثلاثة ينشئ الطلب نيابةً عن العميل.
 *
 * دورة الحالة:
 *  new (جديد) → assigned (مُكلّف به فني) → in_progress (جارٍ) →
 *  completed (مكتمل) → closed (مغلق/مقيّم) | cancelled (ملغى)
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();

            // رقم تسلسلي مقروء للعميل مثل: REQ-2024-00001
            $table->string('reference')->unique();

            // نوع الطلب
            $table->enum('type', ['maintenance', 'fault', 'inspection'])
                  ->index();

            // المصعد المرتبط بالطلب (ومنه نصل للمبنى والعميل)
            $table->foreignId('elevator_id')
                  ->constrained('elevators')
                  ->cascadeOnDelete();

            // العميل (مكرر هنا لتسهيل الاستعلام والتقارير)
            $table->foreignId('customer_id')
                  ->constrained('customers');

            // من أنشأ الطلب (عميل أو موظف)
            $table->foreignId('created_by')->constrained('users');

            // الفني المعيّن (يبقى null حتى يتم التعيين)
            $table->foreignId('assigned_technician_id')
                  ->nullable()
                  ->constrained('users');

            // من قام بالتعيين (المدير أو أحد المشرفين)
            $table->foreignId('assigned_by')
                  ->nullable()
                  ->constrained('users');
            $table->timestamp('assigned_at')->nullable();

            $table->string('title');                 // عنوان مختصر
            $table->text('description')->nullable(); // وصف العطل/الطلب

            // الأولوية
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                  ->default('normal')
                  ->index();

            // الحالة
            $table->enum('status', [
                'new', 'assigned', 'in_progress', 'completed', 'closed', 'cancelled'
            ])->default('new')->index();

            // مواعيد العمل
            $table->timestamp('scheduled_at')->nullable(); // الموعد المجدول للزيارة
            $table->timestamp('completed_at')->nullable();

            // ملاحظات الإغلاق من الفني
            $table->text('resolution_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'priority']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
