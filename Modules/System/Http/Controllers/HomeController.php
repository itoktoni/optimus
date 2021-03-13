<?php

namespace Modules\System\Http\Controllers;

use App\Http\Controllers\Controller;
use Closure;
use Alkhachatryan\LaravelWebConsole\LaravelWebConsole;
use Illuminate\Support\Facades\Cache;
use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Modules\System\Dao\Facades\GroupUserFacades;
use Modules\System\Http\Charts\HomeChart;
use Modules\System\Plugins\Helper;
use Modules\System\Plugins\Response;
use Modules\System\Plugins\Views;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'access', 'verified']);
    }

    public function route()
    {
        $middlewareClosure = function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        };

        $routes = collect(Route::getRoutes());

        foreach (config('pretty-routes.hide_matching') as $regex) {
            $routes = $routes->filter(function ($value, $key) use ($regex) {
                return !preg_match($regex, $value->uri());
            });
        }

        return view(Views::form('route', 'home'), [
            'routes' => $routes,
            'middlewareClosure' => $middlewareClosure,
        ]);
    }

    public function console()
    {
        return LaravelWebConsole::show();
    }

    public function dashboard()
    {
        if (!config('website.application') && auth()->user()->group_user == 'customer') {
            return redirect('/');
        }

        $username = auth()->user()->username;
        
        $chart = new HomeChart();
        $chart->labels(['One', 'Two', 'Three', 'Four']);
        $chart->dataset('My dataset', 'line', [1, 2, 3, 4])->backgroundColor('#0088cc')->fill(true);
        $chart->dataset('My dataset 2', 'line', [4, 3, 2, 1])->backgroundColor('#ddf1fa')->fill(true);
        $chart->options([
            'tooltip' => [
                'show' => true // or false, depending on what you want.
            ]
        ]);

        return view(Views::form('dashboard', 'home'))->with(['chart' => $chart]);
    }

    public function configuration()
    {
        $frontend = array_map('basename', File::directories(resource_path('views/frontend/')));
        $backend  = array_map('basename', File::directories(resource_path('views/backend/')));
        if (!Cache::has('group')) {
            Cache::rememberForever('group', function () {
                return DB::table(GroupUserFacades::getTable())->get();
            });
        }

        $mail_driver = array("smtp", "sendmail", "mailgun", "mandrill", "ses", "sparkpost", "log", "array", "preview");

        $session_driver = ["file", "cookie", "database", "redis"];
        $cache_driver   = ["apc", "database", "file", "redis"];

        $database_driver = [
            "sqlite" => 'SQlite',
            "mysql"  => 'MySQL',
            "pgsql"  => 'PostgreSQL',
            "sqlsrv" => 'SQL Server',
        ];
        
        if (request()->isMethod('POST')) {
            $data = [

                'debug' => request()->get('debug'),
                'env' => request()->get('env'),
                'address' => request()->get('address'),
                'description' => request()->get('description'),
                'seo' => request()->get('seo'),
                'promo' => request()->get('promo'),
                'footer' => request()->get('footer'),
                'header' => htmlentities(request()->get('header')),
                'color' => request()->get('color'),
                'colors' => request()->get('colors'),
                'backend' => request()->get('backend'),
                'frontend' => request()->get('frontend'),
                'owner' => request()->get('owner'),
                'phone' => request()->get('phone'),
                'live' => request()->get('live'),
                'name' => request()->get('name'),
                'email' => request()->get('email'),
                'cache' => request()->get('website_cache'),
                'session' => request()->get('website_session'),
                'developer_setting' => request()->get('developer_setting'),
            ];
            
            Config::write('website', $data);

            if (request()->exists('favicon')) {
                $file   = request()->file('favicon');
                $favicon   = config('app.name') . '_favicon.' . $file->extension();
                $file->storeAs('logo', $favicon);
                Config::write('website', ['favicon' => $favicon]);
            }

            if (request()->exists('logo')) {
                $file   = request()->file('logo');
                $name   = config('app.name') . '_logo.' . $file->extension();
                $simpen = $file->storeAs('logo', $name);
                Config::write('website', ['logo' => $name]);
            }

            session()->put('success', 'Configuration Success !');
            return Response::redirectBack();
        }

        return view(Views::form('configuration', 'home'))->with([
            'group'           => Cache::get('group'),
            'frontend'        => array_combine($frontend, $frontend),
            'backend'         => array_combine($backend, $backend),
            'database'        => env('DB_CONNECTION'),
            'mail_driver'     => array_combine($mail_driver, $mail_driver),
            'session_driver'  => array_combine($session_driver, $session_driver),
            'cache_driver'    => array_combine($cache_driver, $cache_driver),
            'database_driver' => $database_driver,
        ]);
    }

}
