<?php

namespace Mini\Cms\Modules\Modal;

class MissingDefaultValueForUnNullableColumn extends \Exception
{
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}