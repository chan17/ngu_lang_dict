<?php

namespace App\Admin\Extensions\Column;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class OpenShortDesc extends AbstractDisplayer
{
    public function display(\Closure $callback = null)
    {
        $callback = $callback->bindTo($this->row);

        list($param) = call_user_func($callback);

        $key = $this->getKey();
        // $name = $this->column->getName();


        Admin::script($this->script());

        $str = '<button class="btn btn-xs btn-default ui_shor_desc" data-key="'.$key.'_shor_desc'.'" data-toggle="modal" data-target="#grid-modal-detail-'.$key.'_shor_desc'.'">更多</button>';
        $str .= '<div class="modal" id="grid-modal-detail-'.$key.'_shor_desc'.'" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">';
        $str .= '<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close">';
        $str .= '<span aria-hidden="true">×</span></button><h4 class="modal-title">简介</h4></div><div class="modal-body">';
        $str .= '<div id="grid-map-'.$key.'_shor_desc'.'" style="height:450px;overflow: auto">'.$param.'</div>';
        $str .= '</div></div></div></div>';
        if(mb_strlen($param)>32){
            return mb_substr($param,0,32).$str;
        }else{
            return $param;
        }

    }

    protected function script()
    {
        return <<<EOT

$('.ui_shor_desc').on('click', function() {

    
});

EOT;
    }
}
