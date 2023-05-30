<?php

namespace App\Models;
use App\Models\Address;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Buyer extends Model
{
    use HasFactory;

    protected $fillable = ['first_name', 'last_name', 'email'];
    
    public function address() : MorphMany
    {
        
        return $this->morphMany(Address::class, 'addresable');

    }

    //Since buyer has one to many polymorphic relationship with the Address,->
    //I need the latest address of the buyer
    public function latestAddress() : MorphOne
    {

        return $this->morphOne(Address::class, 'addresable')->latestOfMany();
    }

    public function order() : HasMany
    {

        return $this->hasMany(Order::class);
    }

    public function item() 
    {

        return  $this->belongsTo(Item::class);
    }


}
