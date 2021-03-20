<?php

namespace Modules\Item\Dao\Facades;

use Modules\Item\Plugins\Helper;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Facade;

class ActionFacades extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Str::snake(Helper::getClass(__CLASS__));
    }
}
