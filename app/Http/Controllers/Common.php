<?php

namespace App\Http\Controllers;

use App\Tools\OssTools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Common extends Controller
{
    // yiru_metas.name =》 new model  => title   id
    public function getMetaData(Request $request){
        $metaName = $request->get('q');
        if (empty($metaName) ) return [''=>''];

        $resMdel = \App\Models\Yiru\YiruMetas::where(['name'=>$metaName])->first()->toArray();
        if (empty($resMdel)||empty($resMdel['group'])||empty($resMdel['model'])) {
            return [''=>''];
        }
        $param = json_decode($resMdel['model'],true);
        $tableName = !empty($param['table'])?$param['table']:$metaName;

        if ($resMdel['group']=='db_yiru_data') {
            $DBname = '';
        }else{
            $DBname = $resMdel['group'];
        }
        try {
            if(! \Schema::connection($DBname)->hasTable($tableName)){
                return [''=>''];
            }
        } catch (\Exception $e) {
            return [''=>''];
        }
        
        if(empty($param['keyname'])){
            return [''=>''];
        }else{
            $keyname = $param['keyname'];
        }
        $modelSchema = \DB::connection($DBname)->table($tableName);
        // dd(\Schema::connection($DBname)->hasColumn($tableName, 'title'));
        if(\Schema::connection($DBname)->hasColumn($tableName, 'title')){  //check whether users table has email column
            $fieldTitle = 'title as text';
        }else{
            // dd($tableName,$DBname);
            $fieldTitle = "$keyname AS text";
        }
        if (!empty($param['refer']['filter'])) {
            foreach ($param['refer']['filter'] as $key => $value) {
                $whereFilter[$key][]=$value['keyname'];
                $whereFilter[$key][]='=';
                $whereFilter[$key][]=$value['keyvalue'];
            }
        }else{
            $whereFilter =[];
        }
        // dd($keyname);
        $resMdel = $modelSchema->whereNull('deleted_at')->where($whereFilter)
        ->get(["{$keyname} AS id" ,$fieldTitle])->toArray();
        array_unshift($resMdel,['id'=>' ','text'=>'无']);
        return $resMdel;
    }

    public function getMetaOption(Request $request,$group){
        /* $yiru_meta = $request->get('q');
        if(empty($yiru_meta)){
            return [''=>''];
        }*/
        $resMdel = \DB::table('yiru_options')
        ->orderBy('listorder', 'desc')
        ->whereNull('deleted_at')->where(['group'=>$group])->get(["name AS id" ,'title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>' ','text'=>'无']);
            return $resMdel;
        }
    }

    public function getGroupByMeta(Request $request){
        $meta_group = $request->get('q');
        if(empty($meta_group)){
            return [''=>''];
        }
        $resMdel = \DB::table('yiru_groups')
        ->orderBy('listorder', 'desc')
        ->whereNull('deleted_at')->where(['meta_group'=>$meta_group])->get(["group_id AS id" ,'title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>' ','text'=>'无']);
            return $resMdel;
        }
    }
    public function getGoodsByType(Request $request){
        $good_type = $request->get('q');
        if(empty($good_type)){
            return [''=>''];
        }
        $resMdel = \App\Models\Yiru\YiruGoods::where(['good_type'=>$good_type])
        ->get(["good_id AS id" ,'title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>' ','text'=>'无']);
            return $resMdel;
        }
    }

    public function getGoodsByProduct(Request $request){
        $product_id = $request->get('q');
        if(empty($product_id)){
            return [''=>''];
        }
        $resMdel = \App\Models\Yiru\YiruGoods::where(['product_id'=>$product_id])
        ->get(["good_id AS id" ,'title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>' ','text'=>'无']);
            return $resMdel;
        }
    }

    public function getCatCodesByMch(Request $request,$meta_cat){
        $yiru_mch_id = $request->get('q');
        if(empty($yiru_mch_id)){
            return [''=>''];
        }
        $resMdel =  \App\Models\Yiru\YiruCatCodes::orderBy('yiru_cat_codes.listorder', 'desc')
        ->join('yiru_cats', 'yiru_cat_codes.yiru_cat_id', '=', 'yiru_cats.cat_id')
        ->where(['yiru_cat_codes.meta_cat'=>$meta_cat,'yiru_cat_codes.yiru_mch_id'=>$yiru_mch_id])->get(["id" ,'yiru_cats.title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>' ','text'=>'无']);
            return $resMdel;
        }
    }
    public function getCatCodes(Request $request,$meta_cat){
        $resMdel =  \App\Models\Yiru\YiruCatCodes::orderBy('yiru_cat_codes.listorder', 'desc')
        ->join('yiru_cats', 'yiru_cat_codes.yiru_cat_id', '=', 'yiru_cats.cat_id')
        ->where(['yiru_cat_codes.meta_cat'=>$meta_cat])->get(["id" ,'yiru_cats.title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>'ANY','text'=>'不限']);
            return $resMdel;
        }
    }
    public function getCatCodeCodeById(Request $request){
        $id = $request->get('q');
        if(empty($id)){
            return [''=>''];
        }
        $resMdel =  \App\Models\Yiru\YiruCatCodes::where(['id'=>$id])->get(["code AS idascode" ,'code AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            return $resMdel;
        }
    }

    public function getSelectByMeta(Request $request,$tableName){
        $meta = $request->get('q');
        if(empty($meta)){
            return [''=>''];
        }
        if (substr($tableName,-1) != 's') {
            $table = $tableName .'s';
        }else{
            $table = $tableName;
        }
        // dd(substr($tableName,0,5) );exit;
        if (substr($tableName,0,5) == 'meta_') {
            $where = $tableName;
        }else {
            $where = 'meta_'.$tableName;            
        }
        $resMdel = \DB::table('yiru_'.$table)
        ->whereNull('deleted_at')->where([$where=>$meta])->get(["{$tableName}_id AS id" ,'title AS text'])->toArray();
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            array_unshift($resMdel,['id'=>' ','text'=>'无']);
            return $resMdel;
        }
    }
    
    public function getMetaOptionParam(Request $request){
        $option_id = $request->get('q');

        $param = \DB::table('yiru_options')
        ->whereNull('deleted_at')->where(['option_id'=>$option_id])->value('param');
        if (empty($param)) {
            return [''=>''];
        }
        // dump(json_decode($param,true)['option']);exit;
        $result = json_decode($param,true)['option']['filter'];
        if (empty($result)) {
            return [''=>''];
        }
        foreach ($result as $key => $value) {
            $resultEnd[$key]['id'] = $value;
            $resultEnd[$key]['text'] =   $value;
            
        }
        array_unshift($resultEnd,['id'=>' ','text'=>'无']);
        return $resultEnd;
    }

    //   meta_type  => meta_type_id
    public function getMetaTypeIdByType(Request $request/* ,$yiru_meta */){
        $meta_type = $request->get('q');
        if(empty($meta_type)){
            return [''=>''];
        }

        $resMdel = \DB::table('yiru_types')
        ->orderBy('listorder', 'desc')
        ->whereNull('deleted_at')->where(['meta_type'=>$meta_type/* ,'yiru_meta'=>$yiru_meta */])
        ->select("meta_type_id AS id",\DB::raw("CONCAT(yiru_meta,' | ',title) AS text"))
        // ->select("meta_type_id AS id",'title AS text')
        ->get();

        if (empty($resMdel)) {
            return [''=>''];
        }else{
            return $resMdel;
        }
    }

    public function getCatTitleByGroupid(Request $request/* ,$yiru_meta */){
        $group_name = $request->get('q');
        if(empty($group_name)){
            return [''=>''];
        }
        $group_id = \App\Models\Yiru\YiruGroups::where(["name"=>$group_name,'meta_group'=>'business_group'])->value('group_id');
        if(empty($group_id)){
            return [''=>''];
        }
        // dd($group_id);
        $resMdel = \DB::table('yiru_group_cats')
        ->whereNull('yiru_group_cats.deleted_at')->where(['group_id'=>$group_id])
        ->join('yiru_cats', 'yiru_group_cats.yiru_cat_id', '=', 'yiru_cats.cat_id')
        ->select("yiru_cats.name AS id","yiru_cats.title AS text")
        ->get();
        
        // dd($resMdel);
        if (empty($resMdel)) {
            return [''=>''];
        }else{
            return $resMdel;
        }
    }

    public function getMchByType(Request $request){
        $mch_type = $request->get('q');
        if(empty($mch_type)){
            return [''=>''];
        }

        $resMdel = \DB::table('yiru_mchs')
        ->orderBy('listorder', 'desc')
        ->whereNull('deleted_at')->where(['mch_type'=>$mch_type])->get(["mch_id AS id" ,'title AS text']);

        if (empty($resMdel)) {
            return [''=>''];
        }else{
            return $resMdel;
        }
    }
    public function uploadImg(Request $request)
    {
        $file = $request->file("mypic");
        // dd($file);
        if (!empty($file)) {
            foreach ($file as $key => $value) {
                $len = $key;
            }
            if ($len > 25) {
                return response()->json(['ResultData' => 6, 'info' => '最多可以上传25张图片']);
            }
            $m = 0;
            $k = 0;
            for ($i = 0; $i <= $len; $i++) {
                // $n 表示第几张图片
                $n = $i + 1;
                if ($file[$i]->isValid()) {
                    if (in_array(strtolower($file[$i]->extension()), ['jpeg', 'jpg', 'gif', 'gpeg', 'png'])) {
                        $picname = $file[$i]->getClientOriginalName();//获取上传原文件名
                        $ext = $file[$i]->getClientOriginalExtension();//获取上传文件的后缀名
                        // 重命名
                        $filename = time() . str_random(6) . "." . $ext;
                        $result = $this->oss_zip($file[$i]->getRealPath(),$filename);
                        if ($result) {
                            $newFileName = $result;
                            $m = $m + 1;
                            // return response()->json(['ResultData' => 0, 'info' => '上传成功', 'newFileName' => $newFileName ]);

                        } else {
                            $k = $k + 1;
                            // return response()->json(['ResultData' => 4, 'info' => '上传失败']);
                        }
                        $msg = $m . "张图片上传成功 " . $k . "张图片上传失败<br>";
                        $return[] = ['ResultData' => 0, 'info' => $msg, 'newFileName' => $newFileName];
                    } else {
                        return response()->json(['ResultData' => 3, 'info' => '第' . $n . '张图片后缀名不合法!<br/>' . '只支持jpeg/jpg/png/gif格式']);
                    }
                } else {
                    return response()->json(['ResultData' => 1, 'info' => '第' . $n . '张图片超过最大限制!<br/>' . '图片最大支持2M']);
                }
            }

        } else {
            return response()->json(['ResultData' => 5, 'info' => '请选择文件']);
        }
        return $return;
    }
    public function oss_zip($url,$new) {
        $paths = $url;
        $oss = new OssTools();
        $ss = $oss->uploadFile($paths,'acitvitys/'.$new);
        if($ss) {
            return 'http://slff.oss-cn-hangzhou.aliyuncs.com/acitvitys/'.$new;
        }
    }
}