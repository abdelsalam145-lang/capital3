<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * تقييم العميل للفني بعد انتهاء الطلب.
 * عند انتقال الطلب إلى completed يُرسل للعميل طلب تقييم،
 * وعند تقييمه ينتقل الطلب إلى closed.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('technician_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('maintenance_request_id')
                  ->unique()                 // تقييم واحد لكل طلب
                  ->constrained('maintenance_requests')
                  ->cascadeOnDelete();

            $table->foreignId('technician_id')->constrained('users');
            $table->foreignId('customer_id')->constrained('customers');

            $table->unsignedTinyInteger('rating'); // 1 إلى 5
            $table->text('comment')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technician_ratings');
    }
};
