<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'addresable_id',
        'addresable_type',
        'country',
        'state',
        'street',
        'local_government',
        'user_id'

    ];


    public function addresable() 
    {
        return $this->morphTo();
    }

    
    public function item()
    {

        return $this->belongsTo(Item::class) ;
    }

}

    
