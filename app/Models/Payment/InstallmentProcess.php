<?php

namespace App\Models\Payment;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallmentProcess extends Model
{
    use HasFactory;

    protected $appends = [
        'body_installment'
    ];

    protected function bodyInstallment(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }
}
