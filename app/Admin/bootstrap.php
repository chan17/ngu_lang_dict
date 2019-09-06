<?php

/**
 * Laravel-admin - admin builder based on Laravel.
 * @author z-song <https://github.com/z-song>
 *
 * Bootstraper for Admin.
 *
 * Here you can remove builtin form field:
 * Encore\Admin\Form::forget(['map', 'editor']);
 *
 * Or extend custom form field:
 * Encore\Admin\Form::extend('php', PHPEditor::class);
 *
 * Or require js and css assets:
 * Admin::css('/packages/prettydocs/css/styles.css');
 * Admin::js('/packages/prettydocs/js/main.js');
 *
 */
use Encore\Admin\Form;
use Encore\Admin\Grid\Column;


Encore\Admin\Form::forget(['map', 'editor']);

use App\Admin\Extensions\Column\OpenMap;
use App\Admin\Extensions\Column\OpenView;
use App\Admin\Extensions\Column\OpenViewImg;
use App\Admin\Extensions\Form\WangEditor;
use App\Admin\Extensions\Form\SearchMap;

app('view')->prependNamespace('admin', resource_path('views/admin'));

// Form::extend('WangEditor', WangEditor::class);
Form::extend('SearchMap', SearchMap::class);
// 扩展 `ztree` 控件(树状控件)
Column::extend('Orgdetail', Orgdetail::class);
Column::extend('OpenViewImg', OpenViewImg::class);
Column::extend('openView', OpenView::class);
// Form::extend('ztree', Ztree::class);
// Form::extend('ueditor', UEditor::class);
// 引入 `layer` 控件


// // sun 引入自己加的js
// Admin::js('/packages/admin/custom/main.js');
// // 引入 `layer` 插件
// Admin::js('/packages/admin/layer/layer.js');

// Admin::js('/custom-templates/datatables/js/admin/jquery.dataTables.js');
// Admin::js('/custom-templates/datatables/js/admin/dataTables.bootstrap.js');
// Admin::js('/custom-templates/datatables/js/admin/colResizable-1.5.min.js');