<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019/1/21
 * Time: 9:35
 */

namespace App\Component\Classes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AutoListorder
{
    public $model;
    public $fieldValue = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * 设置分组键值
     * @param array $fieldValue
     * @return $this
     */
    public function setFieldValue(array $fieldValue = []){
        $this->fieldValue = $fieldValue;
        return $this;
    }

    /**
     * 返回listorder
     * @return int|mixed
     */
    public function getlistOrder(){
        $query = $this->model->query();
        foreach ($this->fieldValue as $field=>$value){
            $query->where($field,$value);
        }
        $listOrder = $query->max('listorder')??0;

        return $listOrder + 1;
    }

}