<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

    public function groups()
    {
        return $this->belongsToMany('App\ItemsGroup');
    }
}
