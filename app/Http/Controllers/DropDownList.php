<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DropDownList extends Controller
{
    public function selectTreeFromOps(Request $request){
        $id = $request->get('q');
        if(empty($id)){
            // dd('nnn');
            // $resMdel['myself'] = ['id'=>'','parent_id'=>'','title'=>''];
            $resMdel['children'] = \DB::table('yiru_sps')
                ->orderBy('listorder', 'desc')
                ->whereNull('deleted_at')->where(['pid'=>null])->get(["yiru_sp_id AS id" ,'pid AS parent_id','title'])->toArray();
                // $resMdel['siblings'][] = ['id'=>'','parent_id'=>'','title'=>''];

        }else{
            $resMdel['myself'] = \DB::table('yiru_sps')
            ->whereNull('deleted_at')->where('yiru_sp_id','=',$id)->get(["yiru_sp_id AS id" ,'pid AS parent_id','title'])->first();
            
            if (empty($resMdel['myself']->parent_id)) {
                $whereaa = [];
                $resMdel['siblings'] = \DB::table('yiru_sps')
                ->orderBy('listorder', 'desc')
                ->whereNull('deleted_at')->where(['pid'=>null])->get(["yiru_sp_id AS id" ,'pid AS parent_id','title'])->toArray();;
            }else{
                $whereaa = ['pid'=>$resMdel['myself']->parent_id];
                $resMdel['siblings'] = \DB::table('yiru_sps')
                ->orderBy('listorder', 'desc')
                ->whereNull('deleted_at')->where($whereaa)->get(["yiru_sp_id AS id" ,'pid AS parent_id','title'])->toArray();
            }
            
            $resMdel['children'] = \DB::table('yiru_sps')
            ->orderBy('listorder', 'desc')
            ->whereNull('deleted_at')->where(['pid'=>$resMdel['myself']->id])->get(["yiru_sp_id AS id" ,'pid AS parent_id','title'])->toArray();
            // dd($resMdel['myself']->parent_id);
            // dd($resMdel['children']);
        }
        return $resMdel;
    }

}