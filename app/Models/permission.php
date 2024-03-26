<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class permission extends Model
{
    use HasFactory;
    protected $guarded =[
        'id'
    ];

    public function user (){
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function role (){
        return $this->belongsTo(role::class,'role_id');
    }

    public function category (){
        return $this->belongsTo(category::class,'category_id');
    }

    public function getRouteKeyId()
    {
        return 'id';
    }
}
