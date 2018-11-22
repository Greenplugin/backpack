<?php

namespace App;

use App\Service\Packing\ItemInterface;
use Illuminate\Database\Eloquent\Model;

class Item extends Model implements ItemInterface
{
    //
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return float
     */
    public function getVolume()
    {
        return $this->volume;
    }
}
