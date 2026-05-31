<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * المستودعات: مخزن رئيسي + مخزون متحرك لكل فني (سيارته).
 * القطع: كتالوج قطع الغيار بأكواده وأسعاره وحدود إعادة الطلب.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');                       // مثل: المستودع الرئيسي
            $table->enum('type', ['main', 'technician', 'branch'])->default('main');
            // إن كان مخزون فني، نربطه بحساب الفني
            $table->foreignId('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('location')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('parts', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();              // كود القطعة
            $table->string('name');
            $table->string('brand')->nullable();          // الماركة المتوافقة مثل: Mitsubishi
            $table->string('category')->nullable();       // تصنيف: كهربائي، ميكانيكي...
            $table->string('unit')->default('piece');     // وحدة القياس: قطعة، متر...

            $table->decimal('cost_price', 12, 2)->default(0);   // تكلفة الشراء
            $table->decimal('sale_price', 12, 2)->default(0);   // سعر البيع للعميل

            // الكمية الإجمالية المتاحة (محسوبة من الحركات، تُخزَّن للأداء)
            $table->decimal('quantity_on_hand', 12, 2)->default(0);
            // حد إعادة الطلب: عند الوصول إليه يُطلق تنبيه
            $table->decimal('reorder_level', 12, 2)->default(0);

            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index('quantity_on_hand');
        });

        // كمية كل قطعة في كل مستودع
        Schema::create('part_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('part_id')->constrained('parts')->cascadeOnDelete();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnDelete();
            $table->decimal('quantity', 12, 2)->default(0);
            $table->timestamps();
            $table->unique(['part_id', 'warehouse_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_stocks');
        Schema::dropIfExists('parts');
        Schema::dropIfExists('warehouses');
    }
};
