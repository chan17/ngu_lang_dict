<?php

namespace App\Admin\Extensions\Column;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\AbstractDisplayer;

class OpenViewImg extends AbstractDisplayer
{
    public function display(\Closure $callback = null, $btn = '')
    {
        $callback = $callback->bindTo($this->row);
        list($previewImg,$paramImg) = call_user_func($callback);
        
        $key = $this->getKey();
        $name = $this->column->getName();

        Admin::script($this->script());
        // <div id="grid-map-$key" style="height:450px;overflow: auto">$param</div>
        return <<<EOT
<img class="img-rounded grid-open-map" src="$previewImg" data-key="{$name}"  data-toggle="modal" data-target="#grid-modal-{$name}-{$key}"
  style="width:auto;max-width:110px;max-height:100px;height:auto;cursor: pointer;"
>

<div class="modal" id="grid-modal-{$name}-{$key}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">$btn</h4>
      </div>
      <div class="modal-body">
        <img src="$paramImg" class="img-polaroid" style="width:100%">
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
EOT;
    }

    protected function script()
    {
        return <<<EOT

$('.grid-open-map').on('click', function() {

    
});

EOT;
    }
}
