<?php

namespace App\Dto;

use Spatie\DataTransferObject\DataTransferObject;

abstract class AbstractDto extends DataTransferObject
{
    protected bool $ignoreMissing = true;
}
