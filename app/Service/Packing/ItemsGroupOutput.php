<?php
declare(strict_types=1);

namespace App\Service\Packing;


class ItemsGroupOutput extends ItemsGroup
{
    /**
     * @param ItemInterface $item
     */
    function addItem(ItemInterface $item)
    {
        $this->items[] = $item;
    }

    /**
     * @return float
     */
    function getVolume()
    {
        $volume = 0;
        foreach ($this->getItems() as $item) {
            $volume += $item->getVolume();
        }
        return floatval($volume);
    }

    /**
     * @return int
     */
    function getCount()
    {
        return count($this->items);
    }
}