var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};

    $(window).ready(function () {
        console.log("dsfsdfsdfsd.js");

    /**
     * 搜索联想。。。
     * 淘宝搜索 API 测试
     */
    //  <script src="../common/bootstrap-suggest.min.js"></script> 
    
    // $("#ui_top_search2").bsSuggest({
    //     indexId: 2,             //data.value 的第几个数据，作为input输入框的内容
    //     indexKey: 1,            //data.value 的第几个数据，作为input输入框的内容
    //     allowNoKeyword: false,  //是否允许无关键字时请求数据。为 false 则无输入时不执行过滤请求
    //     multiWord: true,        //以分隔符号分割的多关键字支持
    //     separator: ",",         //多关键字支持时的分隔符，默认为空格
    //     getDataMethod: "url",   //获取数据的方式，总是从 URL 获取
    //     showHeader: true,       //显示多个字段的表头
    //     autoDropup: true,       //自动判断菜单向上展开
    //     effectiveFieldsAlias:{Id: "序号", Keyword: "关键字"},
    //     url: '//suggest.taobao.com/sug?code=utf-8&extras=1&q=', /*优先从url ajax 请求 json 帮助数据，注意最后一个参数为关键字请求参数*/
    //     jsonp: 'callback',               //如果从 url 获取数据，并且需要跨域，则该参数必须设置
    //     // url 获取数据时，对数据的处理，作为 fnGetData 的回调函数
    //     fnProcessData: function(json){
    //         var index, len, data = {value: []};

    //         if(! json || ! json.result || ! json.result.length) {
    //             return false;
    //         }

    //         len = json.result.length;

    //         for (index = 0; index < len; index++) {
    //             data.value.push({
    //                 "Id": (index + 1),
    //                 "Keyword": json.result[index][0],
    //                 "Count": json.result[index][1]
    //             });
    //         }
    //         console.log('淘宝搜索 API: ', data);
    //         return data;
    //     }
    // }).on('onDataRequestSuccess', function (e, result) {
    //     console.log('onDataRequestSuccess: ', result);
    // }).on('onSetSelectValue', function (e, keyword, data) {
    //     console.log('onSetSelectValue: ', keyword, data);
    // }).on('onUnsetSelectValue', function () {
    //     console.log("onUnsetSelectValue");
    // });



      
    });