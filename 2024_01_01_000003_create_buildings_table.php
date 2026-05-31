<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * سجل تغيّر حالات الطلب: لتتبّع كل تغيير (من أنشأ، من عيّن، من بدأ، من أنهى)
 * هذا مهم جداً للمساءلة والتقارير الزمنية.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('request_status_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_request_id')
                  ->constrained('maintenance_requests')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users'); // من قام بالتغيير
            $table->string('from_status')->nullable();
            $table->string('to_status');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        // مرفقات الطلب (صور العطل من العميل أو من الفني)
        Schema::create('request_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maintenance_request_id')
                  ->constrained('maintenance_requests')
                  ->cascadeOnDelete();
            $table->foreignId('uploaded_by')->constrained('users');
            $table->string('path');                  // مسار الملف
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedInteger('size')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_attachments');
        Schema::dropIfExists('request_status_logs');
    }
};
