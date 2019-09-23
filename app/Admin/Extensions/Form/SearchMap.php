<?php

namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;

class SearchMap extends Field
{
    /**
     * Column name.
     *
     * @var array
     */
    protected $column = [];
    
    protected $view = 'laravel-admin.extensions.form.search-map';

    /**
     * Get assets required by this field.
     *
     * @return array
     */
    public static function getAssets()
    {
        switch (config('admin.map_provider')) {
            case 'tencent':
                $js = '//map.qq.com/api/js?v=2.exp&key='.env('TENCENT_MAP_API_KEY');
                break;
            case 'google':
                // $js = '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key='.env('GOOGLE_API_KEY');
                break;
            case 'yandex':
                $js = '//api-maps.yandex.ru/2.1/?lang=ru_RU';
                break;
            default:
                // $js = '//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key='.env('GOOGLE_API_KEY');
        }

        return compact('js');
    }

    public function __construct($column, $arguments)
    {
        $this->column['lat'] = (string) $column;
        $this->column['lng'] = (string) $arguments[0];

        array_shift($arguments);

        $this->label = $this->formatLabel($arguments);
        $this->id = $this->formatId($this->column);

        /*
         * Google map is blocked in mainland China
         * people in China can use Tencent map instead(;
         */
        switch (config('admin.map_provider')) {
            case 'tencent':
                $this->useTencentMap();
                break;
            case 'google':
                $this->useGoogleMap();
                break;
            case 'yandex':
                $this->useYandexMap();
                break;
            default:
                $this->useGoogleMap();
        }
    }

    public function useGoogleMap()
    {
        $this->script = <<<EOT
        (function() {
            function initGoogleMap(name) {
                var lat = $('#{$this->id['lat']}');
                var lng = $('#{$this->id['lng']}');
    
                var LatLng = new google.maps.LatLng(lat.val(), lng.val());
    
                var options = {
                    zoom: 13,
                    center: LatLng,
                    panControl: false,
                    zoomControl: true,
                    scaleControl: true,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
    
                var container = document.getElementById("map_"+name);
                var map = new google.maps.Map(container, options);
    
                var marker = new google.maps.Marker({
                    position: LatLng,
                    map: map,
                    title: 'Drag Me!',
                    draggable: true
                });
    
                google.maps.event.addListener(marker, 'dragend', function (event) {
                    lat.val(event.latLng.lat());
                    lng.val(event.latLng.lng());
                });
            }
    
            initGoogleMap('{$this->id['lat']}{$this->id['lng']}');
        })();
EOT;
    }

    public function useTencentMap()
    {
        // dump($this->id['lat']);
        // dump($this->id['lng']);
        $this->script = <<<SCRIPT
        var searchService, markers = [];
(function() {
    function initTencentMap(name) {
        var lat = $('#{$this->id['lat']}');
        var lng = $('#{$this->id['lng']}');
        var container = document.getElementById("map_"+name);
        
        var center = new qq.maps.LatLng(30.259440872,120.20364072);
        var map = new qq.maps.Map(container, {
            center: center,
            zoom: 10
        });
        var latlngBounds = new qq.maps.LatLngBounds();

        if( ! lat.val() || ! lng.val()) {
            // ---------------------------
            //设置Poi检索服务，用于本地检索、周边检索
            searchService = new qq.maps.SearchService({
                //设置搜索范围
                // location: "杭州",
                //设置搜索页码为1
                pageIndex: 1,
                //设置每页的结果数为5
                pageCapacity: 5,
                //设置展现查询结构到infoDIV上
                panel: document.getElementById('infoDiv'),
                //设置动扩大检索区域。默认值true，会自动检索指定城市以外区域。
                autoExtend: true,
                //检索成功的回调函数
                complete: function(results) {
                    var infoWin = new qq.maps.InfoWindow({
                        map: map
                    });
                    //设置回调函数参数
                    var pois = results.detail.pois;
                    for (var i = 0, l = pois.length; i < l; i++) {
                        var poi = pois[i];
                        //扩展边界范围，用来包含搜索到的Poi点
                        latlngBounds.extend(poi.latLng);
                        (function(n) {
                            var marker = new qq.maps.Marker({
                                map: map
                            });
                            marker.setPosition(pois[n].latLng);

                            marker.setTitle(i + 1);
                            markers.push(marker);

                            qq.maps.event.addListener(marker, 'click', function() {
                                infoWin.open();
                                infoWin.setContent('<div style="width:280px;height:100px;">' + pois[n].name + ':'
                                + pois[n].address + '<br/> <span class="label label-success">已选择当前位置<\/span><\/div>');
                                infoWin.setPosition(pois[n].latLng);

                                $('#{$this->id['lat']}').val(pois[n].latLng.lat);
                                $('#{$this->id['lng']}').val(pois[n].latLng.lng);
                                $('#address').val(pois[n].address);
                            });
                            
                        })(i);
                    }
                    //调整地图视野
                    map.fitBounds(latlngBounds);
                },
                //若服务请求失败，则运行以下函数
                error: function() {
                    alert("出错了。");
                }
            });
        } /* ! lat.val() || ! lng.val() */

        $(container).on('click', '.js_choose_poi', function(event) { 
            console.log('mkomjo');
            console.log(event);

        });
    }

    initTencentMap('{$this->id['lat']}{$this->id['lng']}');
    
    //清除地图上的marker
        function clearOverlays(overlays) {
            var overlay;
            while (overlay = overlays.pop()) {
                overlay.setMap(null);
            }
        }
        //设置搜索的范围和关键字等属性
        $("#fdfdhfg").click(function(){
    
            var keyword = document.getElementById("map_keyword").value;
            clearOverlays(markers);
            //根据输入的城市设置搜索范围
            searchService.setLocation("杭州");
            //根据输入的关键字在搜索范围内检索
            searchService.search(keyword);
        });
})();    
SCRIPT;

        // return parent::render();

    }

    public function render()
    {
        // dump($this->script);exit;
        \Admin::script($this->script);
        return view($this->getView(), $this->variables());
    }
    
    public function __toString()
    {
        return $this->render();
    }

    public function useYandexMap()
    {
        $this->script = <<<EOT
        (function() {
            function initYandexMap(name) {
                ymaps.ready(function(){
        
                    var lat = $('#{$this->id['lat']}');
                    var lng = $('#{$this->id['lng']}');
        
                    var myMap = new ymaps.Map("map_"+name, {
                        center: [lat.val(), lng.val()],
                        zoom: 18
                    }); 
    
                    var myPlacemark = new ymaps.Placemark([lat.val(), lng.val()], {
                    }, {
                        preset: 'islands#redDotIcon',
                        draggable: true
                    });
    
                    myPlacemark.events.add(['dragend'], function (e) {
                        lat.val(myPlacemark.geometry.getCoordinates()[0]);
                        lng.val(myPlacemark.geometry.getCoordinates()[1]);
                    });                
    
                    myMap.geoObjects.add(myPlacemark);
                });
    
            }
            
            initYandexMap('{$this->id['lat']}{$this->id['lng']}');
        })();
EOT;
    }
}
