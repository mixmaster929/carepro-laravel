<?php

namespace App\Http\Controllers;

use App\Email;
use App\Invoice;
use App\Lib\DemoBuilder;
use App\Lib\HelperTrait;
use App\Permission;
use Illuminate\Http\Request;

class TestController extends Controller
{
    use  HelperTrait;
    public function index(){
       $builder = new DemoBuilder();
       // $builder->templateOptions();
//echo serialize([]);
     //   echo 'Done';
    }
}
