<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('job_applications')) {
            return;
        }

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_vacancy_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('nik', 20);
            $table->string('gender', 20);
            $table->date('birth_date');
            $table->string('source');
            $table->string('phone', 30);
            $table->text('domicile_address');
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('village');
            $table->unsignedBigInteger('expected_salary');
            $table->string('cv_path');
            $table->string('cv_original_name');
            $table->string('education_level', 20);
            $table->string('education_institution');
            $table->string('major');
            $table->string('gpa', 20);
            $table->text('work_experience');
            $table->boolean('statement_accepted')->default(false);
            $table->boolean('privacy_accepted')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
