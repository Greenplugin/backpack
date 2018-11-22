<?php
declare(strict_types=1);

namespace App\Service\Packing;


class ItemsGroupInput extends ItemsGroup
{
    /**
     * @var int minimal of item count in bag
     */
    private $minCnt;

    /**
     * ItemsGroupInput constructor.
     * @param $items ItemInterface[]
     * @param $minCnt int
     */
    public function __construct($items, $minCnt)
    {
        parent::__construct($items);
        $this->minCnt = $minCnt;
    }

    /**
     * @return int
     */
    public function getMinCnt()
    {
        return $this->minCnt;
    }
}
