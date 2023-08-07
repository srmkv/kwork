<?php

namespace App\Models;


use App\Models\Order\AdmissionDocument;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use HasFactory;


    protected $appends = [
        'user_documents'
    ];

    protected function userDocuments(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    // документы на зачисление(в конкретном потоке)
    public function admissionDocuments()
    {
        return $this->hasMany(AdmissionDocument::class);
    }




}
