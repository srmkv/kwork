<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlowType extends Model
{
    use HasFactory;

    const FILTER = 'Особенность обучения';
    const FILTER_TITLE = 'Особенность курса';

    protected $table = 'flow_types';
    protected $hidden = [
        'created_at',
        'updated_at',
        'pivot',
    ];
    protected $fillable = [
        'name'
    ];

    public function flows()
    {
        return $this->hasMany(Flow::class, 'type_id');
    }
}
