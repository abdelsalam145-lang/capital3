<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * جدول المدفوعات: كل دفعة مرتبطة بفاتورة.
 * تشمل الدفع الإلكتروني عبر بوابة (Moyasar/Tap/PayTabs) أو النقدي/التحويل.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->string('reference')->unique();   // PAY-2024-00001

            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers');

            $table->decimal('amount', 12, 2);
            $table->string('currency', 3)->default('SAR');

            // طريقة الدفع
            $table->enum('method', ['cash', 'bank_transfer', 'card', 'online_gateway', 'cheque'])
                  ->default('online_gateway');

            // حالة الدفعة
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])
                  ->default('pending')
                  ->index();

            // بيانات بوابة الدفع الإلكترونية
            $table->string('gateway')->nullable();          // moyasar | tap | paytabs
            $table->string('gateway_payment_id')->nullable(); // معرّف العملية لدى البوابة
            $table->json('gateway_response')->nullable();     // الرد الكامل (للتدقيق)

            $table->foreignId('recorded_by')->nullable()->constrained('users'); // من سجّل دفعة يدوية
            $table->timestamp('paid_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
