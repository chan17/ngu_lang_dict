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

    public function getMeta($name){
        $oneMeta = \App\Models\Yiru\YiruMetas::where('name',$name)->first()->toArray();
        // dd($oneMeta);
        $model = json_decode($oneMeta['model'],true);

        $result['group']=$oneMeta['group'];
        $result['table']=$model['table'];
        $result['keyname']=$model['keyname'];
        return $result;
    }

    protected static function getMetaName($yiru_meta,$id){
        //查询yiru_meta映射表名
        if (empty($id) || empty($yiru_meta) || $id=='00000000') return $id;
        $model = \App\Models\Yiru\YiruMetas::where('name',$yiru_meta)->value('model');

        if(!$model || !$json = json_decode($model,true)) return $id;
        
        $oneMeta = \App\Models\Yiru\YiruMetas::where('name',$yiru_meta)->first()->toArray();
        if ($oneMeta['group']=='db_yiru_data') {
            $DBname = '';
        }else{
            $DBname = $oneMeta['group'];
        }
        try {
            if(! \Schema::connection($DBname)->hasTable($yiru_meta)){
                return $id;
            }
        } catch (\Exception $e) {
            return $id;
        }
        if(!empty($json['table']) and !empty($json['primary'])){
            if(empty($json['refer']['label'])) return $id;
            // dd($json);
            return \DB::connection($DBname)->table($json['table'])->where($json['primary']['key'],$id)->value($json['refer']['label']);
        }else{
            if(empty($json['keyname'])) return $id;
            return \DB::connection($DBname)->table($yiru_meta)->where($json['keyname'], $id)->value('title');
        }

    }
}