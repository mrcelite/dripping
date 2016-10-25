<?php
namespace App\Http\Controllers\Manager;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\LogicException;

class IndexController extends InitController
{

    public function index (Request $request)
    {
        return view('manager.index',
            [
                'title' => '管理后台'
            ]);
    }
}