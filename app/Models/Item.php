<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [

        'user_id',
        'name',
        'price',
        'description',
        'image',
        'is_sold',
        'item_quantity',

    ];

    public function users() : BelongsToMany
    {

        return $this->belongsToMany(User::class, 'order', 'order_id', 'user_id');

    }

    public function address() 
    {

        return  $this->hasOne(Address::class, 'id', 'user_id');
    }


}
