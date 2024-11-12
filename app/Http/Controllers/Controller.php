<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public static function action() {
		$chunks = explode("@",Route::currentRouteAction());
		return end($chunks);
    }
    
    public static function controller() {
	 	$chunks = explode("\\",Route::currentRouteAction());
		$controller = explode("@",end($chunks));
		return $controller[0]; 
    }
}

class DzHelper extends Controller
{

}
