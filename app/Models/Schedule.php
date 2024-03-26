<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','buyer_id','date', 'is_active' ,'start_time','end_time'];

    // Relasi dengan user
    public function buyer(){
        return $this->belongsTo(User::class,'buyer_id');
    }

    public function seller(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function orderValidations()
    {
        return $this->hasMany(OrderValidation::class, 'schedule_id');
    }
}
