<?php
/**
 * Created by PhpStorm.
 * User: jack
 * Date: 7/4/2018
 * Time: 5:10 AM
 */

namespace App\Admin\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('admin.home.index');
    }
}