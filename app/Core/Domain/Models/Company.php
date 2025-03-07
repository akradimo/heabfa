<?php

namespace App\Core\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'شرکت_ها';

    protected $fillable = [
        'نام',
        'شناسه_ملی',
        'شماره_ثبت',
        'کد_اقتصادی',
        'تلفن',
        'آدرس',
        'لوگو',
        'فعال',
        'سال_مالی_شروع',
        'سال_مالی_پایان',
        'واحد_پول',
        'زبان'
    ];

    protected $casts = [
        'فعال' => 'boolean',
        'سال_مالی_شروع' => 'date',
        'سال_مالی_پایان' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    public function کاربران()
    {
        return $this->belongsToMany(User::class, 'کاربران_شرکت_ها')
                    ->withPivot(['نقش', 'دسترسی_ها'])
                    ->withTimestamps();
    }

    public function حساب_ها()
    {
        return $this->hasMany(Account::class, 'شرکت_id');
    }

    public function اسناد()
    {
        return $this->hasMany(Document::class, 'شرکت_id');
    }

    public function مشتریان()
    {
        return $this->hasMany(Customer::class, 'شرکت_id');
    }

    public function تامین_کنندگان()
    {
        return $this->hasMany(Supplier::class, 'شرکت_id');
    }

    public function محصولات()
    {
        return $this->hasMany(Product::class, 'شرکت_id');
    }

    public function فاکتورها()
    {
        return $this->hasMany(Invoice::class, 'شرکت_id');
    }

    public function دریافت_پرداخت_ها()
    {
        return $this->hasMany(Payment::class, 'شرکت_id');
    }

    public function getSalMaliAttribute()
    {
        return [
            'شروع' => $this->سال_مالی_شروع,
            'پایان' => $this->سال_مالی_پایان
        ];
    }

    public function scopeفعال($query)
    {
        return $query->where('فعال', true);
    }
}