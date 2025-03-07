<?php

namespace App\Core\Domain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $table = 'اسناد';

    protected $fillable = [
        'شماره_سند',
        'تاریخ',
        'نوع',
        'شرح',
        'وضعیت',
        'شرکت_id',
        'ایجاد_کننده_id',
        'تایید_کننده_id'
    ];

    protected $casts = [
        'تاریخ' => 'datetime:Y-m-d H:i:s',
        'وضعیت' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    const وضعیت_پیش_نویس = 0;
    const وضعیت_در_انتظار_تایید = 1;
    const وضعیت_تایید_شده = 2;
    const وضعیت_رد_شده = 3;

    public function تراکنش_ها()
    {
        return $this->hasMany(Transaction::class, 'سند_id');
    }

    public function پیوست_ها()
    {
        return $this->hasMany(Attachment::class, 'سند_id');
    }

    public function ایجاد_کننده()
    {
        return $this->belongsTo(User::class, 'ایجاد_کننده_id');
    }

    public function تایید_کننده()
    {
        return $this->belongsTo(User::class, 'تایید_کننده_id');
    }

    public function شرکت()
    {
        return $this->belongsTo(Company::class, 'شرکت_id');
    }

    public function جمع_بدهکار()
    {
        return $this->تراکنش_ها()->sum('بدهکار');
    }

    public function جمع_بستانکار()
    {
        return $this->تراکنش_ها()->sum('بستانکار');
    }

    public function آیا_متوازن_است()
    {
        return $this->جمع_بدهکار() === $this->جمع_بستانکار();
    }
}