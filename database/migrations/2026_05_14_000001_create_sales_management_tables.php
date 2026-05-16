<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
        });

        Schema::create('sales_commission_rules', function (Blueprint $table) {
            $table->id();
            $table->string('category', 50)->unique();
            $table->decimal('commission_percent', 8, 2)->default(0);
            $table->unsignedBigInteger('commission_fixed')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('marketing_targets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('target_type', 20);
            $table->unsignedSmallInteger('period_year');
            $table->unsignedTinyInteger('period_month')->nullable();
            $table->unsignedBigInteger('target_amount');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'target_type', 'period_year', 'period_month'], 'marketing_targets_period_index');
        });

        Schema::create('marketing_sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category', 50);
            $table->string('product_name');
            $table->unsignedBigInteger('deal_price');
            $table->string('customer_name');
            $table->date('deal_date');
            $table->text('notes')->nullable();
            $table->string('status', 20)->default('pending');
            $table->decimal('commission_percent', 8, 2)->default(0);
            $table->unsignedBigInteger('commission_fixed')->default(0);
            $table->unsignedBigInteger('commission_total')->default(0);
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_note')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status', 'deal_date']);
            $table->index(['category', 'status', 'deal_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_sales');
        Schema::dropIfExists('marketing_targets');
        Schema::dropIfExists('sales_commission_rules');

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
