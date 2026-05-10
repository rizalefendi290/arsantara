<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            if (!Schema::hasColumn('carousels', 'label_color')) {
                $table->string('label_color', 7)->nullable()->after('label');
            }

            if (!Schema::hasColumn('carousels', 'title_color')) {
                $table->string('title_color', 7)->nullable()->after('title');
            }

            if (!Schema::hasColumn('carousels', 'text_color')) {
                $table->string('text_color', 7)->nullable()->after('text');
            }
        });
    }

    public function down(): void
    {
        Schema::table('carousels', function (Blueprint $table) {
            foreach (['label_color', 'title_color', 'text_color'] as $column) {
                if (Schema::hasColumn('carousels', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
