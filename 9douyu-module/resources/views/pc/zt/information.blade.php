@extends('pc.common.layout')
@section('title','平台数据-九斗鱼,心安而有余')
@section('content')
    <div class="data-box">
        <div class="wrap">
            <ul class="data-main" id="datamove1">
                <li>
                    <p><i class="iconfont">&#xe680;</i></p>
                    <p><span class="data" data-rel="{{$totalAmount}}" >0</span></p>
                    <p>交易总额(元)</p>
                </li>
                <li>
                    <p><i class="iconfont">&#xe67e;</i></p>
                    <p><span class="data" data-rel="{{$borrow['investTotal']}}">0</span></p>
                    <p>交易总笔数</p>
                </li>
                <li>
                    <p><i class="iconfont">&#xe67f;</i></p>
                    <p><span class="data" data-rel="{{$borrow['borrowNumber']}}">0</span></p>
                    <p>总借款人数</p>
                </li>
                <li>
                    <p><i class="iconfont">&#xe681;</i></p>
                    <p><span class="data" data-rel="{{$borrow['investNumber']}}">0</span></p>
                    <p>总出借人数</p>
                </li>
            </ul>

            <div class="data-inner">
                <div class="data-title">平均数据</div>
                <ul class="data-inner-main" id="datamove2">
                    <li>
                        <p>人均出借额(元)</p>
                        <p><span class="data" data-rel="{{round($totalAmount/$borrow['investNumber'])}}">0</span></p>
                    </li>
                    <li>
                        <p>笔均出借额(元)</p>
                        <p><span class="data" data-rel="{{round($totalAmount/$borrow['investTotal'])}}">0</span></p>
                    </li>
                    <li>
                        <p>人均借款额(元)</p>
                        <p><span class="data" data-rel="{{round($totalAmount/$borrow['borrowNumber'])}}">0</span></p>
                    </li>
                    <li>
                        <p>笔均借款额(元)</p>
                        <p><span class="data" data-rel="{{round($totalAmount/$borrow['borrowTotal'])}}">0</span></p>
                    </li>
                </ul>

            </div>

            <div class="data-inner">
                <div class="data-title">待收数据</div>
                <ul class="data-inner-main data-w3" id="datamove3">
                    <li>
                        <p>当前待收金额(元)</p>
                        <p><span class="data" data-rel="{{(int)$collect['principal']+(int)$collect['interest'] + (int)$currentCashTotal}}">0</span></p>
                    </li>
                    <li>
                        <p>当前待收本金(元)</p>
                        <p><span class="data" data-rel="{{(int)$collect['principal'] +(int)$currentCashTotal}}">0</span></p>
                    </li>
                    <li>
                        <p>当前待收利息(元)</p>
                        <p><span class="data" data-rel="{{(int)$collect['interest']}}">0</span></p>
                    </li>
                </ul>

            </div>

            <div class="data-map"  id="datamove4">
                <div class="data-title2">平均满标天数</div>
                <div id="dataDay" class="dataday"></div>
                <div class="dataday-mark">
                    <p class="dataday-mark-color dataday-mark-color1"><span>1月期</span><em>{{isset($hundred['101']['avg_date']) ? $hundred['101']['avg_date'] : '0.8'}}天</em></p>
                    <p class="dataday-mark-color dataday-mark-color2"><span>1~3月期</span><em>{{isset( $hundred['200']['avg_date'] ) ? $hundred['200']['avg_date'] : '1.2'}}天</em></p>
                    <p class="dataday-mark-color dataday-mark-color3"><span>3月期</span><em>{{isset($hundred['103']['avg_date']) ? $hundred['103']['avg_date'] : '1.7'}}天</em></p>
                    <p class="dataday-mark-color dataday-mark-color4"><span>6月期</span><em>{{isset($hundred['106']['avg_date']) ? $hundred['106']['avg_date'] : '1.4'}}天</em></p>
                    <p class="dataday-mark-color dataday-mark-color5"><span>12月期及以上</span><em>{{isset($hundred['112']['avg_date']) ? $hundred['112']['avg_date'] : '2.1'}}天</em></p>
                </div>
            </div>
            <div class="data-map"  id="datamove5">
                <div class="data-title2">出借人在全国34个地区分布情况</div>
                <div id="dataArea" class="dataArea"></div>
                <div class="dataArea-mark">
                    <p class="dataday-mark-color dataday-mark-color6">广东：12.77%</p>
                    <p class="dataday-mark-color dataday-mark-color7">北京：8.04%</p>
                    <p class="dataday-mark-color dataday-mark-color8">浙江：7.78%</p>
                    <p class="dataday-mark-color dataday-mark-color9">江苏：6.87%</p>
                    <p class="dataday-mark-color dataday-mark-color10">山东：5.86%</p>
                    <p class="dataday-mark-color dataday-mark-color11">其余：58.68%</p>
                </div>
            </div>
            <div class="data-refresh">数据统计截止日期：{{$nowDay}}</div>
        </div>
    </div>

@endsection

@section('jspage')
<script type="text/javascript" src="{{ assetUrlByCdn('/static/js/nummove.js')}}"></script>
<script type="text/javascript" src="{{ assetUrlByCdn('/static/js/echarts-all.js')}}"></script>
<script type="text/javascript">

(function($){
    $(function(){
        $.numberMove();
    })
})(jQuery)

//出借人地图分布
var myChartArea = echarts.init(document.getElementById('dataArea'));
var myChartDay = echarts.init(document.getElementById('dataDay'));

var optionArea = {
    tooltip: {
        trigger: 'item',
        formatter: function(params) {
            var value = value || 0;
            if(params.value.substr(1)>0){
                return params.name + '<br/>' + params.value.substr(1)+'%' ;
            }else{
                return params.name + '<br/>--';
            }
        }
    },
    series : [
        {
            name: '九斗鱼出借人分布',
            type: 'map',
            mapType: 'china',
            mapLocation: {
                x: 'left'
            },
            // selectedMode : 'multiple',
            itemStyle: {
                normal: {
                    borderWidth:1,
                    borderColor:'#fff',
                    color: '#cae3ff',
                    label: {
                        show: true,
                        textStyle: {
                            color: '#666666'
                        }
                    }
                },
                emphasis: {                 // 也是选中样式
                    borderWidth:1,
                    borderColor:'#fff',
                    color: '#2d91ff',
                    label: {
                        show: true,
                        textStyle: {
                            color: '#fff'
                        }
                    }
                }
            }, data:[

            {"name":"广东","value":"12.77","itemStyle":{"normal":{"color":"#2d91ff"}}},
            {"name":"北京","value":"8.04","itemStyle":{"normal":{"color":"#4ca1ff"}}},
            {"name":"浙江","value":"7.78","itemStyle":{"normal":{"color":"#6cb2ff"}}},
            {"name":"江苏","value":"6.87","itemStyle":{"normal":{"color":"#8cc3ff"}}},
            {"name":"山东","value":"5.86","itemStyle":{"normal":{"color":"#abd3ff"}}},
            {"name":"上海","value":"4.46"},
            {"name":"河北","value":"4.11"},
            {"name":"福建","value":"3.93"},
            {"name":"河南","value":"3.80"},
            {"name":"山西","value":"3.60"},
            {"name":"湖南","value":"3.27"},
            {"name":"湖北","value":"3.17"},
            {"name":"四川","value":"3.14"},
            {"name":"陕西","value":"2.97"},
            {"name":"辽宁","value":"2.75"},
            {"name":"安徽","value":"2.25"},
            {"name":"黑龙江","value":"2.17"},
            {"name":"云南","value":"2.06"},
            {"name":"天津","value":"2.02"}
        ]
        }
    ],
    animation: true
};
myChartArea.setOption(optionArea);


optionDay = {
    tooltip : {
        trigger: 'item',
        formatter: "{b} : <br/>{d}%"
    },

    series : [
        {
            name:'平均满标天数',
            type:'pie',
            radius : ['40%', '70%'],
            itemStyle : {
                normal : {
                    label : {
                        show : false
                    },
                    labelLine : {
                        show : false
                    }
                }
            },
            data:[
                {value:{{isset($hundred['101']['total']) ? $hundred['101']['total']: '301'}}, name:'1月期',"itemStyle":{"normal":{"color":"#438aff"}}},
                {value:{{isset($hundred['200']['total']) ? $hundred['200']['total']: '200'}}, name:'1~3月期',"itemStyle":{"normal":{"color":"#00c582"}}},
                {value:{{isset($hundred['103']['total']) ? $hundred['103']['total']: '303'}}, name:'3月期',"itemStyle":{"normal":{"color":"#ff7148"}}},
                {value:{{isset($hundred['106']['total']) ? $hundred['106']['total']: '306'}}, name:'6月期',"itemStyle":{"normal":{"color":"#ffb300"}}},
                {value:{{isset($hundred['112']['total']) ? $hundred['112']['total']: '112'}}, name:'12月期及以上',"itemStyle":{"normal":{"color":"#cadcff"}}}
            ]
        }
    ]
};
myChartDay.setOption(optionDay);

</script>
@endsection
