<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShoppingOffer extends Model
{
    use HasFactory;

    public $table = 'text_block_shopping_offer';
    protected $guarded = [];
    public $timestamps = false;
}
