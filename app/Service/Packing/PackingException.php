<?php
declare(strict_types=1);

namespace App\Service\Packing;


use Throwable;

class PackingException extends \Exception
{

    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct('Too much volume of items', $code, $previous);
    }
}