<?php
namespace app\admin\controller;

use app\AdminBaseController;
use think\facade\Session;
use think\facade\View;

class User extends AdminBaseController
{
    public function index()
    {
        // if (!Session::get('name'))
        // {
        //     return redirect(app('http')->getName()."/login/login");
        // }
        return View::fetch();
    }


    public function add()
    {
        // if (!Session::get('name'))
        // {
        //     return redirect(app('http')->getName()."/login/login");
        // }
        return View::fetch();
    }


}