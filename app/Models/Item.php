<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Models\User;
use App\Models\Review;

class Item extends Model
{
    use HasFactory;

    protected $fillable=['name','price', 'ram', 'memory','cpu', 'gpu', 'image', 'category_id', 'manufacturer_id','user_id'];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function manufacturer(){
        return $this->belongsTo(Manufacturer::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function reviews(){
        return $this->hasMany(Review::class);
    }

    public function usersCart(){
        return $this->belongsToMany(User::class)
        ->withPivot('quantity', 'ram', 'memory', 'in_cart')->withTimestamps();
    }
}
