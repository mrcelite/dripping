<?php
namespace App\Http\Routes;

use Illuminate\Contracts\Routing\Registrar;

class ManagerRoutes
{
    public function map(Registrar $router)
    {
        $router->group(['domain' => 'dripping.inner.xiyibang.com'], function ($router) {
            $router->get('/manager/login', ['as' => 'login', 'uses' => 'Manager\LoginController@index']);
            $router->post('/manager/login/login', ['as' => 'login.login', 'uses' => 'Manager\LoginController@login']);
            $router->get('/manager/register', ['as' => 'register', 'uses' => 'Manager\RegisterController@index']);
            $router->get('/manager/register/register', ['as' => 'register.register', 'uses' => 'Manager\RegisterController@register']);
            $router->get('/manager/register/detect', ['as' => 'register.detect', 'uses' => 'Manager\RegisterController@detect']);
            $router->get('/home/show/{id}', ['name' => 'home.show', 'uses' => 'Home\HomeController@show']);
            $router->get('/manager/index', ['name' => 'manager.index', 'uses' => 'Manager\IndexController@index']);
            $router->get('/manager/album', ['name' => 'manager.album', 'uses' => 'Manager\AlbumController@index']);
            $router->post('/manager/album/album', ['name' => 'manager.album.album', 'uses' => 'Manager\AlbumController@album']);
            $router->get('/manager/album/add', ['name' => 'manager.album.add', 'uses' => 'Manager\AlbumController@add']);
            $router->post('/manager/album/create', ['name' => 'manager.album.create', 'uses' => 'Manager\AlbumController@create']);
            $router->post('/manager/album/remove', ['name' => 'manager.album.remove', 'uses' => 'Manager\AlbumController@remove']);
            $router->post('/manager/album/update', ['name' => 'manager.album.update', 'uses' => 'Manager\AlbumController@update']);
            $router->get('/manager/album/recommend', ['name' => 'manager.album.recommend', 'uses' => 'Manager\AlbumController@recommend']);
            $router->get('/manager/wall/add', ['name' => 'manager.wall.add', 'uses' => 'Manager\WallController@add']);
            $router->post('/manager/wall/create', ['name' => 'manager.wall.create', 'uses' => 'Manager\WallController@create']);
            $router->post('/manager/wall/remove', ['name' => 'manager.wall.remove', 'uses' => 'Manager\WallController@remove']);
            $router->post('/manager/wall/update', ['name' => 'manager.wall.update', 'uses' => 'Manager\WallController@update']);
            $router->get('/manager/wall', ['name' => 'manager.wall.index', 'uses' => 'Manager\WallController@index']);
            $router->get('/manager/category', ['name' => 'manager.category', 'uses' => 'Manager\CategoryController@index']);
            $router->get('/manager/category/add', ['name' => 'manager.category.add', 'uses' => 'Manager\CategoryController@add']);
            $router->post('/manager/category/create', ['name' => 'manager.category.create', 'uses' => 'Manager\CategoryController@create']);
            $router->post('/manager/category/update', ['name' => 'manager.category.modify', 'uses' => 'Manager\CategoryController@update']);
            $router->post('/manager/category/remove', ['name' => 'manager.category.modify', 'uses' => 'Manager\CategoryController@remove']);
        });
    }
}