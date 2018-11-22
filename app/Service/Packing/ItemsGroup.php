<?php
declare(strict_types=1);

namespace App\Service\Packing;


use Illuminate\Contracts\Support\Arrayable;

class ItemsGroup implements Arrayable
{
    /**
     * @var ItemInterface[]
     */
    protected $items;

    /**
     * ItemsGroup constructor.
     * @param $items ItemInterface[]
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    /**
     * @return ItemInterface[]
     */
    public function getItems()
    {
        return $this->items;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }
}
