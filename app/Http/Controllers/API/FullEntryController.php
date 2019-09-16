<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use \Yurun\Util\Chinese;

class FullEntryController extends Controller
{
    // 請求方法：get
    // 參數： keyword
    public function index(Request $request){
        $requestData = $request->all();
        $requestData['keyword'] = trim($requestData['keyword']);
        if (empty($requestData['keyword'])) {
            return $this->buildJson(false, '請填寫參數keyword');
        }
        
        // 简体转换成繁体
        // $requestData['keyword'] = Chinese::toTraditional($requestData['keyword'])[0];
        // dd($requestData['keyword']);
        
        $resEntries = DB::table('entries')->whereNull('deleted_at')->where('title', 'like', '%'.$requestData['keyword'].'%')
        ->get(['entry_id' ,'title'/* ,'explanation','example' */])->toArray();

        return $this->buildJson(true, '', $resEntries);

    }

    // 請求方法：get
    // 參數： entry_id
    public function detail(Request $request){
        $requestData = $request->all();
        if (!is_numeric($requestData['entry_id'])) {
            return $this->buildJson(false, 'entry_id錯誤');
        }

        $result = collect(DB::table('entri es')->whereNull('deleted_at')->where('entry_id', '=', $requestData['entry_id'])
        ->select(['entry_id' ,'title','explanation','example'])->first())->toArray();
        if (empty($result)) {
            return $this->buildJson(false, '個詞條弗存在');         
        }
        
        $result['phonetics'] = DB::table('phonetics')->whereNull('deleted_at')->where('entry_id', '=', $requestData['entry_id'])
        ->orderBy('region_type')
        ->get(['phonetic_id' ,'region_type','value'])->toArray();
        
        return $this->buildJson(true, '', $result);
    }
}
