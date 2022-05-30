<?php

namespace App\Repository\Shopify;

use App\Models\Picture;
use App\Repository\AbstractRepository;

class PictureRepository extends AbstractRepository
{
    /**
     * @return Picture
     */
    protected function model(): string
    {
        return Picture::class;
    }
}
