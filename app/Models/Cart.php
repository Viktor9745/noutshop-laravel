<?php

namespace App\Models;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Cart extends Pivot
{
    use HasFactory;

    protected $table = 'item_user';

    protected $fillable = ['item_id', 'user_id', 'quantity', 'ram', 'memory', 'in_cart'];

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
