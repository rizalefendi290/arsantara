<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            if (!Schema::hasColumn('carousels', 'placement')) {
                $table->string('placement')->default('content')->after('id');
            }

            if (!Schema::hasColumn('carousels', 'page_key')) {
                $table->string('page_key')->nullable()->after('placement');
            }

            if (!Schema::hasColumn('carousels', 'label')) {
                $table->string('label')->nullable()->after('title');
            }

            if (!Schema::hasColumn('carousels', 'text')) {
                $table->text('text')->nullable()->after('label');
            }

            if (!Schema::hasColumn('carousels', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('link_url');
            }

            if (!Schema::hasColumn('carousels', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('sort_order');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            foreach (['placement', 'page_key', 'label', 'text', 'sort_order', 'is_active'] as $column) {
                if (Schema::hasColumn('carousels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
