<?php

namespace App\Models\Order;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ReservedDocument extends Model
{
    use HasFactory;
    protected $appends = [
        'reserved_docs',
        'order_body'
    ];

    protected $guarded = [];

    // временно дефолтный json 
    protected $attributes = [
            'reserved_docs' => '{
                "passports": [],
                "higher_diploms": [],
                "snils" : [],
                "schools" : [],
                "secondary_diploms" : [],
                "other_documents" : [],
                "employmentHistory" : [],
                "additional_diploms" : []
            }',

            'order_body' => '{
                "order_id" : null, 
                "type_order" : "personal",
                "admin_id" : 150,
                "user_id" : 0
            }'
    ];

    protected function reservedDocs(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    protected function orderBody(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
