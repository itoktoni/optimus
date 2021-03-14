<?php

namespace Modules\System\Plugins;

class Notes
{
    const create = 'Create';
    const update = 'Update';
    const delete = 'Delete';
    const error = 'Error';
    const data = 'List';
    const single = 'Single';
    const token = 'Token';

    public static function data($data = null)
    {
        $log['status'] = true;
        $log['code'] = 200;
        $log['name'] = self::data;
        $log['data'] = $data;
        return $log;
    }

    public static function single($data = null)
    {
        $log['status'] = true;
        $log['code'] = 200;
        $log['name'] = self::single;
        $log['data'] = $data;
        return $log;
    }

    public static function create($data = null)
    {
        $log['status'] = true;
        $log['code'] = 201;
        $log['name'] = self::create;
        $log['data'] = $data;
        return $log;
    }

    public static function token($data = null)
    {
        $log['status'] = true;
        $log['code'] = 200;
        $log['name'] = self::token;
        $log['data'] = $data;
        return $log;
    }

    public static function update($data = null)
    {
        $log['status'] = true;
        $log['code'] = 200;
        $log['name'] = self::update;
        $log['data'] = $data;
        if(request()->wantsJson()){
            $log['data'] = is_array($data) ? $data : $data->toArray();
        }
        return $log;
    }
    
    public static function delete($data = null)
    {
        $log['status'] = true;
        $log['code'] = 204;
        $log['name'] = self::delete;
        $log['data'] = $data;
        return $log;
    }
    
    public static function error($data = null)
    {
        $log['status'] = false;
        $log['code'] = 400;
        $log['name'] = self::error;
        $log['data'] = $data;
        return $log;
    }
}
