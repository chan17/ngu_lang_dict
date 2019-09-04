<?php

if (!function_exists('get_status_map_name')) {
    /**
     * 根据状态获取状态映射名称
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    function get_status_map_name($status)
    {
        switch ($status) {
            case 'to_editing':
                return '<span class="badge bg-green">等待编辑</span>';
                break;
            case 'to_apply_online':
                return '<span class="badge bg-green">等待上线申请</span>';
                break;
            case 'to_apply_offline':
                return '<span class="badge bg-green">等待下线申请</span>';
                break;
            case 'to_apply_online_publish':
                return '<span class="badge bg-green">等待上线发布</span>';
                break;
            case 'to_apply_offline_publish':
                return '<span class="badge bg-green">等待下线发布</span>';
                break;
            case 'to_check_online':
                return '<span class="badge bg-green">上线审核中</span>';
                break;
            case 'to_check_offline':
                return '<span class="badge bg-green">下线审核中</span>';
                break;
            case 'to_check_publish':
                return '<span class="badge bg-green">发布审核中</span>';
                break;
            default:
                return '';
                break;
        }
    }
}

if (!function_exists('get_publish_map_name')) {
    /**
     * 根据状态获取状态映射名称
     * @param  [type] $status [description]
     * @return [type]         [description]
     */
    function get_publish_map_name($publish)
    {
        switch ($publish) {
            case 'to_check_publish':
                return '<span class="badge bg-green">发布审核中</span>';
                break;
            case 'to_publish':
                return '<span class="badge bg-green">等待发布</span>';
                break;
            case 'published':
                return '<span class="badge bg-green">已发布</span>';
                break;
            default:
                return '';
                break;
        }
    }
}

if (!function_exists('proxy_http_request')) {
    /**
     * [proxy_http_request 请求转发]
     * @param  [type] $type [description]
     * @param  [type] $url  [description]
     * @param  array  $data [description]
     * @return [type]       [description]
     */
    function proxy_http_request($type, $url, $data = [])
    {
        $ch = curl_init();
        if (strtoupper($type) === 'GET') {
            // 设置选项，包括URL
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
        } else if (strtoupper($type) === 'POST') {
            $header = [
                "Accept: application/json",
                "Content-Type: application/json;charset=utf-8",
            ];
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            // 设置url
            curl_setopt($ch, CURLOPT_URL, $url);

            // TRUE, 将curl_exec()获取的信息以字符串返回, 而不是直接输出
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // 超时时间
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            // curl如果需要进行毫秒超时
            // curl_easy_setopt(curl, CURLOPT_NOSIGNAL,1L);
            // 或者curl_setopt ( $ch,  CURLOPT_NOSIGNAL,true);//支持毫秒级别超时设置
            // 设置发送方式:post
            curl_setopt($ch, CURLOPT_POST, 1);
            // 设置发送数据
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        // 执行cURL会话
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
            // print curl_error($ch);
        }
        //释放curl句柄
        curl_close($ch);
        // 在这里处理要还给
        // $response = json_decode($response, true);
        return $response;
    }
}


if (!function_exists('change_list_order')) {
    function change_list_order($request,$model){
        $input = $request->all();
        $info = $model::find($input['id']);
        if(!$info){
            return response()->json(['status' => 'error', 'content' => '无效操作']);
        }
        switch ($input['action']){
            case 'up':
                //查询上一条的排序
                $upInfo = $model::query()
                    ->where('listorder','>',$info->listorder)
                    ->orderBy('listorder')
                    ->first();

                DB::beginTransaction();
                if($upInfo){
                    $listorder = $info->listorder;
                    $uplistorder = $upInfo->listorder;

                    try{
                        if(!$info->update(['listorder'=>$uplistorder])){
                            DB::rollBack();
                            return response()->json(['status' => 'error', 'content' => '操作失败']);
                        }
                        if(!$upInfo->update(['listorder'=>$listorder])){
                            DB::rollBack();
                            return response()->json(['status' => 'error', 'content' => '操作失败']);
                        }

                        DB::commit();
                        return response()->json(['status' => 'success', 'content' => '操作成功']);

                    }catch (\Exception $e){
                        DB::rollBack();
                        return response()->json(['status' => 'error', 'content' => '操作失败']);
                    }
                }else{
                    return response()->json(['status' => 'error', 'content' => '已经是最高排序，无需操作']);
                }

                break;
            case 'down':
                //查询下一条的排序
                $downInfo = $model::query()
                    ->where('listorder','<',$info->listorder)
                    ->orderBy('listorder','desc')
                    ->first();
                DB::beginTransaction();

                if($downInfo){
                    $listorder = $info->listorder;
                    $downlistorder = $downInfo->listorder;
                    try{
                        if(!$info->update(['listorder'=>$downlistorder])){
                            DB::rollBack();
                            return response()->json(['status' => 'error', 'content' => '操作失败']);
                        }

                        if(!$downInfo->update(['listorder'=>$listorder])){
                            DB::rollBack();
                            return response()->json(['status' => 'error', 'content' => '操作失败']);
                        }

                        DB::commit();
                        return response()->json(['status' => 'success', 'content' => '操作成功']);
                    }catch (\Exception $e){
                        DB::rollBack();
                        return response()->json(['status' => 'error', 'content' => '操作失败']);
                    }
                }else{
                    return response()->json(['status' => 'error', 'content' => '已经是最低排序，无需操作']);
                }
                break;
        }

    }


}
