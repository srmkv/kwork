<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $table = 'banners';
    protected $guarded = [];

    public function media()
    {
        return $this->hasMany(BannerMedia::class);
    }

}
