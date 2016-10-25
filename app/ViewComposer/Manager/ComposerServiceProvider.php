<?php

namespace App\ViewComposer\Manager;

use App\Http\Controllers\Manager\InitController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     * @return void
     */
    public function boot()
    {
        View()->composer('*',function($view){
            $resourcesHost  = $_ENV['RESOURCES_HOST'];
            $imgHost        = $_ENV['IMG_HOST'];
            $oInit = new InitController();
            $view->with('userId',$oInit->_userId);
            $view->with('userName',$oInit->_userName);
            $view->with('realName',$oInit->_realName);
            $view->with('mobileName',$oInit->_mobilePhone);
            $view->with('userModule',$oInit->_userModule);
            $view->with('resourcesHost',$resourcesHost);
            $view->with('imgHost',$imgHost);
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

