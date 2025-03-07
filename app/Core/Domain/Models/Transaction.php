<?php

namespace App\Core\Domain\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    
    protected $fillable = [
        'تاریخ',
        'حساب_id',
        'سند_id',
        'بدهکار',
        'بستانکار',
        'شرح',
        'شماره_ارجاع'
    ];

    protected $casts = [
        'تاریخ' => 'datetime:Y-m-d H:i:s',
        'بدهکار' => 'decimal:2',
        'بستانکار' => 'decimal:2'
    ];

    public function حساب()
    {
        return $this->belongsTo(Account::class, 'حساب_id');
    }

    public function سند()
    {
        return $this->belongsTo(Document::class, 'سند_id');
    }
}