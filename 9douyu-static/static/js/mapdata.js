//投资者地图分布

var myChart = echarts.init(document.getElementById('main'));
        var option = {
    
   
    series : [
        {
            name: '九斗鱼投资者分布',
            type: 'map',
            mapType: 'china',
            mapLocation: {
                x: 'left'
            },
            selectedMode : 'multiple',
            itemStyle:{
                normal:{label:{show:false}},
                emphasis:{label:{show:true}}
            },
            itemStyle: {
                normal: {
                    borderWidth:1,
                    borderColor:'#fff',
                    color: '#fbe9e3',
                    label: {
                        show: false
                    }
                },
                emphasis: {                 // 也是选中样式
                    borderWidth:1,
                    borderColor:'#fff',
                    color: '#ed6c44',
                    label: {
                        show: true,
                        textStyle: {
                            color: '#fff'
                        }
                    }
                }
            },
            data:[
                {name:'广东', 
                value:45597,
            	 itemStyle: {
                    normal: {
                        color: '#ed6c44'
                        
	                }
	            }},
                {name:'浙江', 
                value:27198,
            	 itemStyle: {
                    normal: {
                        color: '#e98261'
                    }
                }},               
                {name:'北京', 
                value:27091,
            	 itemStyle: {
                    normal: {
                        color: '#ed9c82'
                    }
                }},
                {name:'江苏',
                 value:23703,
            	 itemStyle: {
                    normal: {
                        color: '#f2b5a2'
                        
                    }
                }},
                {name:'山东', 
                value:19786,
            	 itemStyle: {
                    normal: {
                        color: '#f6cfc2'
                        
                    }
                }},
            ]
        }
    ],
    animation: true
};
        myChart.setOption(option);