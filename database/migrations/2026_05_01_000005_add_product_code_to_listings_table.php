<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('listings', 'product_code')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->string('product_code')->nullable()->unique()->after('id');
            });
        }

        DB::table('listings')
            ->whereNull('product_code')
            ->orderBy('id')
            ->select(['id', 'category_id'])
            ->chunkById(100, function ($listings) {
                foreach ($listings as $listing) {
                    $prefix = match ((int) $listing->category_id) {
                        1 => 'RMH',
                        2 => 'TNH',
                        3 => 'MBL',
                        4 => 'MTR',
                        default => 'PRD',
                    };

                    DB::table('listings')
                        ->where('id', $listing->id)
                        ->update([
                            'product_code' => $prefix.'-'.str_pad((string) $listing->id, 6, '0', STR_PAD_LEFT),
                        ]);
                }
            });
    }

    public function down(): void
    {
        if (Schema::hasColumn('listings', 'product_code')) {
            Schema::table('listings', function (Blueprint $table) {
                $table->dropUnique(['product_code']);
                $table->dropColumn('product_code');
            });
        }
    }
};
