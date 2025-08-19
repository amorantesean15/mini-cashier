<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['item_id', 'quantity']; // para pwede mass assign

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
