<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'buyer_id',
        'price',
        'schedule_id',
        'timer_expiry'
    ];

    public function buyer(){
        return $this->belongsTo(User::class,'buyer_id');
    }

    public function seller(){
        return $this->belongsTo(User::class,'seller_id');
    }

    public function schedule(){
        return $this->belongsTo(Schedule::class,'schedule_id');
    }
}
