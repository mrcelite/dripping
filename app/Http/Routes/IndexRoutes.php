<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

namespace App\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class IndexRoutes
{
    public function map(Registrar $router)
    {
        $router->group(['domain' => 'dripping.inner.xiyibang.com'], function ($router) {

            $router->get('/', function () {
                return 'hello world';
            });

        });
    }
}