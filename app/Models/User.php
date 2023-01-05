<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Item;
use App\Models\Review;

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
        'role_id',
        'is_active',
        'ava',
    ];

    public function items(){
        return $this->hasMany(Item::class);
    }
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

    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function role(){
        return $this->belongsTo(Role::class);
    }
    public function itemsCart(){
        return $this->belongsToMany(Item::class)
        ->withPivot('quantity','ram','memory','in_cart')->withTimestamps();
    }
    // public function itemsWithStatus($in_cart){
    //     return $this->belongsToMany(Item::class, 'item_user')
    //     ->wherePivot('in_cart', $in_cart)
    //     ->withTimestamps()
    //     ->withPivot('quantity', 'ram', 'memory', 'in_cart');
    // }

}
