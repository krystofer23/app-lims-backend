<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'ruc',
        'name',
        'direction',
        'activity',
        'category',
        'state',
        'is_supplier',
        'is_partner',
        'observations',
    ];

    protected $casts = [
        'is_supplier' => 'boolean',
        'is_partner' => 'boolean',
        'state' => 'boolean',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(PatientCompany::class, 'company_id', 'id');
    }
}
