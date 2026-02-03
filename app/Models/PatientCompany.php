<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientCompany extends Model
{
    use SoftDeletes;

    protected $table = 'patient_company';

    protected $fillable = [
        'user_id',
        'company_id',
        'email',
        'phone',
        'roles',
        'state',
    ];

    protected $casts = [
        'state' => 'boolean',
        'roles' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
