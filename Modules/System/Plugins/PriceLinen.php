<?php

namespace Modules\System\Plugins;

class PriceLinen
{
    public static function create($data = null)
    {
        session()->put(self::success, $data ?? 'Data has been ' . self::create . ' !');
    }
}
