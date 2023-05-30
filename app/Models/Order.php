<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'buyer_id',
        'item_id',
        'quantity'
    ];

    public function buyer() : BelongsTo
    {
        return $this->BelongsTo(Buyer::class);
    }
}
