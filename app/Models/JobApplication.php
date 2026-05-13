<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class JobApplication extends Model
{
    protected $fillable = [
        'job_vacancy_id',
        'full_name',
        'email',
        'nik',
        'gender',
        'birth_date',
        'source',
        'phone',
        'domicile_address',
        'province',
        'city',
        'district',
        'village',
        'expected_salary',
        'cv_path',
        'cv_original_name',
        'education_level',
        'education_institution',
        'major',
        'gpa',
        'work_experience',
        'statement_accepted',
        'privacy_accepted',
        'status',
        'reviewed_at',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'expected_salary' => 'integer',
        'statement_accepted' => 'boolean',
        'privacy_accepted' => 'boolean',
        'reviewed_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::deleting(function (JobApplication $application) {
            if ($application->cv_path) {
                Storage::delete($application->cv_path);
            }
        });
    }

    public function jobVacancy(): BelongsTo
    {
        return $this->belongsTo(JobVacancy::class);
    }
}
