<?php

namespace App\Services;

use App\Models\Course\Faq;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class FaqService
{
    public function deleteChildren(Faq $faq)
    {
        dd($faq->questions()->answer()->dissociate());
        $faq->questions->first()->save();
        dd($faq->questions->first()->answer()->dissociate());
    }
}