<?php
namespace App\Admin\Extensions\Actions;

use Encore\Admin\Admin;
// use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class AutoListorder /* extends AbstractDisplayer */
{
    // public $actions;
    /* public function __construct($actions)
    {
        $this->actions = $actions;
    } */
    // \(new AutoListorder\(new .*init_list_order([\w\W]+)排序下移.*(\r)\n.*
    public static function render($actions){
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        $controller = explode('@', $controllerAction)[0];
        $controller = str_replace("Controller", "", $controller);
        
        $folder  = explode('\\', $routeArray['uses'])[3];
        $routeAsColl  = explode('.', $routeArray['as']);
        $currModle = "\App\Models\\".$folder."\\".$controller;

        // (new \App\Component\Classes\AutoListorder(new $currModle()));

        $maxListorder = $currModle::query()
            ->max('listorder');

        $minListorder = $currModle::query()
            ->min('listorder');

        $url      = route($routeAsColl[0].'.'.$routeAsColl[1].'.'.'change_order_list');

        $listorder = $actions->row->listorder;
        $id     = $actions->getKey();
        // dump($id);exit;
        if($listorder != $maxListorder){
            $function = "changeListorder('$url', 'post', {id: '$id',action: 'up',_token: LA.token})";
            $actions->append('<a href="javascript:;" title="排序上移" onclick="'. $function .'">&nbsp;<i class="fa fa-arrow-up"></i>&nbsp;</a>');
        }

        if($listorder != $minListorder){
            $function = "changeListorder('$url', 'post', {id: '$id',action: 'down',_token: LA.token})";
            $actions->append('<a href="javascript:;" title="排序下移" onclick="'. $function .'">&nbsp;<i class="fa fa-arrow-down"></i>&nbsp;</a>');
        }
    }
    
    // D:\matrix_ubuntu\code\yiru_goods\app\Http\Controllers\Column\YiruRecImgsController.php
    public static function renderWhere($actions,$whereArr=[]){
        $routeArray = app('request')->route()->getAction();
        $controllerAction = class_basename($routeArray['controller']);
        $controller = explode('@', $controllerAction)[0];
        $controller = str_replace("Controller", "", $controller);
        
        $folder  = explode('\\', $routeArray['uses'])[3];
        $routeAsColl  = explode('.', $routeArray['as']);
        $currModle = "\App\Models\\".$folder."\\".$controller;

        (new \App\Component\Classes\AutoListorder(new $currModle()))->init_list_order($currModle);
        // [
        //     ['meta_type', '=', '1'],
        //     ['meta_id', '<>', '1'],
        // ]
        $maxListorder = $currModle::query()
            ->where($whereArr)
            ->max('listorder');

        $minListorder = $currModle::query()
            ->where($whereArr)
            ->min('listorder');

        $url      = route($routeAsColl[0].'.'.$routeAsColl[1].'.'.'change_order_list');

        $listorder = $actions->row->listorder;
        $id     = $actions->getKey();
        // dump($id);exit;
        if($listorder != $maxListorder){
            $function = "changeListorder('$url', 'post', {id: '$id',action: 'up',_token: LA.token})";
            $actions->append('<a href="javascript:;" title="排序上移" onclick="'. $function .'">&nbsp;<i class="fa fa-arrow-up"></i>&nbsp;</a>');
        }

        if($listorder != $minListorder){
            $function = "changeListorder('$url', 'post', {id: '$id',action: 'down',_token: LA.token})";
            $actions->append('<a href="javascript:;" title="排序下移" onclick="'. $function .'">&nbsp;<i class="fa fa-arrow-down"></i>&nbsp;</a>');
        }
    }

}
