<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $fillable = [
        'user_id',
        'ruc',
        'company_name',
        'trade_name',
        'economic_activity',
        'address',
        'country',
        'ubigeo',
        'is_supplier',
        'is_partner',
        'category',
        'billing_start_date',
        'billing_cut_off_period',
        'purchase_order_submission_limit',
        'credit_days',
        'observations',
    ];

    protected $casts = [
        'is_supplier' => 'boolean',
        'is_partner' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
