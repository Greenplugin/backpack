<?php
declare(strict_types=1);

namespace App\Service\Packing;


interface ItemInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return float
     */
    public function getVolume();
}