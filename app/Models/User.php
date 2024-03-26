<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\cart;
use App\Models\category;
use App\Models\EditDisplayed;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'role_id',
        'points',
        'idcardnumber',
        'norekening',
        'idcardstatcode',
        'report_times',
        'ban_status',
        'unban_times'


    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function category()
{
    return $this->belongsTo(Category::class, 'category_id');
}

    public function role()
    {
        return $this->belongsTo(role::class);
    }

    public function cart(){
        return $this->hasMany(cart::class,'buyer_id');
    }

    public function permissions(){
    return $this->hasMany(permission::class, 'user_id', 'id');
    }

    public function permission(){
        return $this->belongsTo(permission::class,'permission_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
    public function availableTimes(): HasMany
    {
        return $this->hasMany(AvailableTime::class);
    }

    // public function updateSingleBlade()
    // {
    //     return $this->hasOne(EditDisplayed::class, 'user_id');
    // }


}
