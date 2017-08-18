var checkTime = function(i)    
{    
   if (i < 10) {    
       i = "0" + i;    
    }    
   return i;    
}  
var changeType = function(type,d,button) {
	if(type == 'foreshow') {
		if(d < 3600) {
			$(".div_publis_time_"+button).hide();
			$(".div_foreshow_"+button).show();
		} else {
			$(".div_publis_time_"+button).show();
			$(".div_foreshow_"+button).hide();
		}
	} 
}
var Groupbuy_Calculation_Time_Init = function() {
    $(".time_left").each(function() {
    	var obj = $(this);
        var c = obj.next(".default_time");
        var h = c.attr("time");
        var button = obj.attr("updateButton");
        var type = obj.attr("type");
        changeType(type, h, button);
        Groupbuy_Calculation_Time(c, h, button, type, obj)
    })
};
var Groupbuy_Calculation_Time = function(c, h, button, type, obj) {
    var d = h;    	
    if (d > 0) {
        var g = parseInt(d / 86400),                         //天
        l = parseInt(d / 3600) - g * 24,                     //时
        e = parseInt(d / 60) - parseInt(d / 3600) * 60,      //分
        i = parseInt(d / 1) - parseInt(d / 60) * 60;         //秒
        c.find(".h").text(checkTime(l+g*24));
        c.find(".m").text(checkTime(e));
        c.find(".s").text(checkTime(i));
        d -= 1;
        c.attr('time',d);
        changeType(type, d,button);
        setTimeout(function() {
            Groupbuy_Calculation_Time(c, d, button, type, obj)
        },
        1E3)
    } else {
    	if(type == 'foreshow') {
    		obj.text('投资剩余时间').attr('type','investIng');
    		c.attr('time',c.attr('invest_time'));
    		var bgDiv = $('.div_foreshow_'+button);
    		var but   = $('#foreshow_'+button);
    		if(typeof but != 'undefined') but.text('我要投资');
    		if(typeof bgDiv != 'undefined') $('.div_'+button).remove();
    		Groupbuy_Calculation_Time_Init();
    	} else {
    		$('#foreshow_'+button).text('投资完成');
    		obj.text('投资完成')
    	}
        
    }
};
