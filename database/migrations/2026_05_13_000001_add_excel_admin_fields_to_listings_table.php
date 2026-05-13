<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            if (!Schema::hasColumn('listings', 'contact_name')) {
                $table->string('contact_name')->nullable()->after('location');
            }

            if (!Schema::hasColumn('listings', 'contact_phone')) {
                $table->string('contact_phone')->nullable()->after('contact_name');
            }

            if (!Schema::hasColumn('listings', 'ownership_name')) {
                $table->string('ownership_name')->nullable()->after('contact_phone');
            }

            if (!Schema::hasColumn('listings', 'document_status')) {
                $table->string('document_status')->nullable()->after('ownership_name');
            }

            if (!Schema::hasColumn('listings', 'annual_tax_status')) {
                $table->string('annual_tax_status')->nullable()->after('document_status');
            }

            if (!Schema::hasColumn('listings', 'five_year_tax_status')) {
                $table->string('five_year_tax_status')->nullable()->after('annual_tax_status');
            }

            if (!Schema::hasColumn('listings', 'ownership_status')) {
                $table->string('ownership_status')->nullable()->after('five_year_tax_status');
            }

            if (!Schema::hasColumn('listings', 'plate_number')) {
                $table->string('plate_number')->nullable()->after('ownership_status');
            }

            if (!Schema::hasColumn('listings', 'seller_price')) {
                $table->bigInteger('seller_price')->nullable()->after('discount_price');
            }

            if (!Schema::hasColumn('listings', 'offer_price')) {
                $table->bigInteger('offer_price')->nullable()->after('seller_price');
            }

            if (!Schema::hasColumn('listings', 'sold_price')) {
                $table->bigInteger('sold_price')->nullable()->after('offer_price');
            }

            if (!Schema::hasColumn('listings', 'minimum_dp')) {
                $table->bigInteger('minimum_dp')->nullable()->after('sold_price');
            }

            if (!Schema::hasColumn('listings', 'minimum_nego_price')) {
                $table->bigInteger('minimum_nego_price')->nullable()->after('minimum_dp');
            }

            if (!Schema::hasColumn('listings', 'showroom_dp_in')) {
                $table->bigInteger('showroom_dp_in')->nullable()->after('minimum_nego_price');
            }

            if (!Schema::hasColumn('listings', 'installment')) {
                $table->bigInteger('installment')->nullable()->after('showroom_dp_in');
            }

            if (!Schema::hasColumn('listings', 'tenor')) {
                $table->string('tenor')->nullable()->after('installment');
            }

            if (!Schema::hasColumn('listings', 'commission')) {
                $table->bigInteger('commission')->nullable()->after('tenor');
            }

            if (!Schema::hasColumn('listings', 'notes')) {
                $table->text('notes')->nullable()->after('commission');
            }

            if (!Schema::hasColumn('listings', 'progress_status')) {
                $table->string('progress_status')->nullable()->after('notes');
            }
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $columns = [
                'contact_name',
                'contact_phone',
                'ownership_name',
                'document_status',
                'annual_tax_status',
                'five_year_tax_status',
                'ownership_status',
                'plate_number',
                'seller_price',
                'offer_price',
                'sold_price',
                'minimum_dp',
                'minimum_nego_price',
                'showroom_dp_in',
                'installment',
                'tenor',
                'commission',
                'notes',
                'progress_status',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('listings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
