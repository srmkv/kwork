<?php

namespace App\Models\Order;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;



class BusinessOrder extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    protected $appends = [
        'order_body',
        'author'
    ];

    protected function orderBody(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }


    protected function author(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    // заявки студентов внутри бизнес заявки
    public function personalOrders()
    {
        return $this->hasMany(Order::class);
    }


    

}
