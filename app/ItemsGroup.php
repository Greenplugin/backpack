<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ItemsGroup extends Model
{
    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function tasks()
    {
        return $this->belongsToMany('App\Task');
    }
}
