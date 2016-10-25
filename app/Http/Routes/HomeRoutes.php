<?php
namespace App\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class HomeRoutes
{
    public function map(Registrar $router)
    {
        $router->group(['domain' => 'dripping.inner.xiyibang.com'], function ($router) {
            $router->get('/home', ['as' => 'home', 'uses' => 'Home\HomeController@index']);
            $router->get('/home/check', ['as' => 'home.check', 'uses' => 'Home\HomeController@check']);
            $router->get('/home/show/{id}', ['name' => 'home.show', 'uses' => 'Home\HomeController@show']);
//            $router->get('/blog', ['as' => 'index.blog', 'uses' => 'BlogController@index']);
//            $router->get('/resume', ['as' => 'index.resume', 'uses' => 'IndexController@resume']);
//            $router->get('/post', ['name' => 'post.show', 'uses' => 'ArticleController@show']);
//            $router->get('/contact', ['as' => 'index.contact', 'uses' => 'IndexController@contact']);
//            $router->post('/contact/comment', ['uses' => 'IndexController@postComment']);
//            $router->get('/travel', ['as' => 'index.travel', 'uses' => 'TravelController@index']);
//            $router->get('/travel/latest', ['as' => 'travel.latest', 'uses' => 'TravelController@latest']);
//            $router->get('/travel/{destination}/list', ['as' => 'travel.destination', 'uses' => 'TravelController@travelList']);
//            $router->get('/travel/{slug}', ['uses' => 'TravelController@travelDetail']);
//            $router->get('/sitemap.xml', ['as' => 'index.sitemap', 'uses' => 'IndexController@sitemap']);
        });
    }
}