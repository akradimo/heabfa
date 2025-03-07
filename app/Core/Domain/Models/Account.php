<?php

namespace App\Core\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $table = 'accounts';
    
    protected $fillable = [
        'کد',
        'عنوان',
        'نوع_حساب',
        'حساب_والد',
        'توضیحات',
        'فعال',
        'مانده',
        'شرکت_id'
    ];

    protected $casts = [
        'مانده' => 'decimal:2',
        'فعال' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function والد()
    {
        return $this->belongsTo(Account::class, 'حساب_والد');
    }

    public function زیرمجموعه‌ها()
    {
        return $this->hasMany(Account::class, 'حساب_والد');
    }

    public function تراکنش‌ها()
    {
        return $this->hasMany(Transaction::class, 'حساب_id');
    }

    public function محاسبه‌مانده()
    {
        return $this->تراکنش‌ها()
            ->selectRaw('SUM(بدهکار) - SUM(بستانکار) as مانده')
            ->first()->مانده ?? 0;
    }
}