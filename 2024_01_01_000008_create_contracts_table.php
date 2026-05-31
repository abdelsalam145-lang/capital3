<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * حركات المخزون: مصدر الحقيقة لكل دخول/خروج/تعديل/تحويل.
 * أي تغيّر في الكمية يجب أن يمرّ عبر حركة، فيبقى السجل قابلاً للتدقيق.
 *
 * استخدام القطع: يربط القطعة المستخدمة بطلب صيانة (لخصمها وفوترتها).
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses');

            // نوع الحركة
            $table->enum('type', [
                'purchase',     // شراء/توريد (إدخال)
                'consumption',  // استهلاك في طلب (إخراج)
                'adjustment',   // تسوية جرد (+/-)
                'transfer_in',  // تحويل وارد من مستودع آخر
                'transfer_out', // تحويل صادر لمستودع آخر
                'return',       // إرجاع للمخزون
            ]);

            // الكمية: موجبة للإدخال، سالبة للإخراج
            $table->decimal('quantity', 12, 2);
            // الكمية بعد الحركة (لقطة لحظية للتدقيق)
            $table->decimal('balance_after', 12, 2)->nullable();

            $table->decimal('unit_cost', 12, 2)->nullable(); // تكلفة الوحدة وقت الحركة

            // المرجع: قد يرتبط بطلب أو بتحويل
            $table->nullableMorphs('reference'); // reference_type + reference_id

            $table->foreignId('performed_by')->nullable()->constrained('users');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['part_id', 'warehouse_id']);
        });

        Schema::create('part_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_request_id')->constrained('maintenance_requests')->cascadeOnDelete();
            $table->foreignId('part_id')->constrained('parts');
            $table->foreignId('warehouse_id')->constrained('warehouses'); // من أي مخزون خرجت
            $table->foreignId('used_by')->constrained('users');            // الفني

            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_price', 12, 2);   // سعر البيع وقت الاستخدام (للفوترة)
            $table->decimal('line_total', 12, 2);   // quantity * unit_price

            // هل أُضيفت لفاتورة؟
            $table->boolean('billed')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_usages');
        Schema::dropIfExists('stock_movements');
    }
};
