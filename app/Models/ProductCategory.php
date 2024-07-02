<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Pivot
{
    //
    public $timestamps = false;

    protected $table = "category_product";

}
