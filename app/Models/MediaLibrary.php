<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    use HasFactory;

    const DEFAULT = 0;
    const BACKGROUND_IMG = 1;
    const BACKGROUND_IMG_ANIMATE = 2;
    const PHOTO = 3;
    const VIDEO = 4;

    public function madiatable()
    {
        return $this->morphTo();
    }
}
