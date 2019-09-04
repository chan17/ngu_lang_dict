<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Tools\OssTools;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function getName(){
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        list($controller, $action) = explode('@', $controllerAction);
        
        $result['controller'] = str_replace("Controller", "", $controller);
        $result['folder']  = explode('\\', $routeArray['uses'])[3];

        return $result;
    }

    public function ossInBaseCtrl($url) {
        $picture = file_get_contents($url);

        if($picture) {
            $oss = new OssTools();
            $file_name = sha1_file($url);
            $ss = $oss->uploadFile($url,'test/'.$file_name.'.png');
            if($ss) {
                return 'http://slff.oss-cn-hangzhou.aliyuncs.com/test/'.$file_name.'.png';;
            }
        }
    }

    public function change_order_list(Request $request){
        $getName = $this->getName();

        $result ="\App\Models\\".$getName['folder']."\\".$getName['controller'];
        return change_list_order($request, $result);
    }

}