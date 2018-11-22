<?php
declare(strict_types=1);

namespace App\Service;


use App\Service\Packing\ItemInterface;
use App\Service\Packing\ItemsGroupInput;
use App\Service\Packing\ItemsGroupOutput;
use App\Service\Packing\PackingException;

class PackingService
{
    /**
     * @param float $volume volume of bag
     * @param ItemsGroupInput[] $items array of items groups
     *
     * @return ItemsGroupOutput[]
     * @throws PackingException
     */
    public function loadBag($volume, $items)
    {
        $result = [];
        $extractedItems = [];
        foreach ($items as $index => $groupInput) {
            $extractedGroup = $groupInput->getItems();
            usort($extractedGroup, function (ItemInterface $a, ItemInterface $b) {
                return $a->getVolume() <=> $b->getVolume();
            });

            for ($i = 0; $i < $groupInput->getMinCnt(); $i++) {
                $volume -= $extractedGroup[$i]->getVolume();
            }
            if ($volume < 0) {
                throw new PackingException();
            }

            $result[$index] = new ItemsGroupOutput(array_splice($extractedGroup, 0, $groupInput->getMinCnt()));
            foreach ($extractedGroup as $item) {
                $extractedItems[] = ['outputGroup' => $result[$index], 'value' => $item];

            }
        }

        if (!count($extractedItems)) {
            return $result;
        }

        usort($extractedItems, function ($a, $b) {
            return $a['value']->getVolume() <=> $b['value']->getVolume();
        });

        foreach ($extractedItems as $item) {
            $volume -= $item['value']->getVolume();
            if ($volume < 0) {
                return $result;
            }
            $item['outputGroup']->addItem($item['value']);
        }

        return $result;
    }
}