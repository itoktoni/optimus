<?php

namespace Modules\System\Plugins;

use PHPUnit\TextUI\Help;

class Views
{
    public static function dashboard($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.dashboard';
    }

    public static function create($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.create';
    }

    public static function update($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.update';
    }

    public static function index($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.index';
    }

    public static function show($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.show';
    }

    public static function backend($file = false)
    {
        $path = 'System::backend.' . config('website.backend') . '.';
        return $file ? $path . $file : $path . 'layout';
    }

    public static function include ($page, $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.form';
    }

    public static function action($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.actions';
    }

    public static function checkbox($page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.checkbox';
    }

    public static function form($form, $page = 'master', $module = 'system')
    {
        return ucfirst($module) . '::page.' . $page . '.' . $form;
    }

    public static function option($option, $placeholder = true, $raw = false, $cache = false)
    {
        $data = Helper::filter($option->dataRepository())->get();

        if (empty($data)) {
            return [];
        }

        if (!$raw) {
            $data = $data->pluck($option->searching, $option->getKeyName());
        }
        if ($placeholder) {
            $data = $data->prepend('- Select ' . Helper::getNameTable($option->getTable()) . ' -', '');
        }

        return $data;
    }
}
