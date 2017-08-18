(function($){	
	//数字变化
	function FloatAdd(f,d){
		var c,b,a;
		try{
			c=f.toString().split(".")[1].length
		}catch(g){c=0}
		try{
			b=d.toString().split(".")[1].length
		}catch(g){b=0}
		a=Math.pow(10,Math.max(c,b));
		return(f*a+d*a)/a
	}
	function FloatMul(d,b){
		var a=0,
		f=d.toString(),
		c=b.toString();
		try{
			a+=f.split(".")[1].length
		}catch(g){}
		try{
			a+=c.split(".")[1].length
		}catch(g){}
		return Number(f.replace(".",""))*Number(c.replace(".",""))/Math.pow(10,a)
	}
	
	/*数字跳动效果*/
	var NumerBeat  = {};
	NumerBeat.add = function(o){
		var n = FloatAdd(Number(o.box.lang),o.k).toFixed(0);
		if(n >= o.v1){
			clearInterval(o.interval);
			o.box.innerHTML = NumerBeat.fixeds(o.v);
			return;
		};
		o.box.lang=n;
		o.box.innerHTML  = NumerBeat.fixeds(n);
	};
	
	NumerBeat.fixeds=function(n){
		var _n=n.toString();
		var _ll=_n.split("");
		var _ll2=[];
		var step=1;
		for(var i=_ll.length-1;i>=0;i--){
			if(step==4){
				step=0;
				_ll2.push(",");
				i++;
			}else{
				_ll2.push(_ll[i]);
			}
			step++;
		}
		_ll2.reverse();
		return _ll2.join("");
	}

	NumerBeat.beat = function(o){	
		if(!o.box){return};
		o.v = o.box.getAttribute("data-rel");//最后的数字	
		o.v1 = Number(o.v);
		var m = parseInt(o.v1/20);	//计数跳动时间，数越大，跳动越慢
		if(m < 1){m = 1}
		o.k = FloatMul(m,0.56).toFixed(0);	
		o.interval = setInterval(function(){NumerBeat.add(o)},20);	
		
	};
	
	NumerBeat.init = function(){
		var beats = $("span.data[data-rel]");
		for(var i = 0 ; i < beats.length; i ++){
			if(beats[i].getAttribute("data-rel")){	
				//if(beats[i].innerHTML<1){
					NumerBeat.beat({box:beats[i]});
//					$(beats[i]).attr("loaded",true);
//				}else{
//					beats[i].innerHTML=beats[i].getAttribute("data-rel");
//					$(beats[i]).attr("loaded",false);
//				}
			};
		};
	};

	

	function numberMove(){
		$(".data").each(function(index, element) {
			var _data = $(this);
			//if(!_data.attr("loaded")){
				var iLength = _data.attr("data-rel").length;
				var sInnerHtml = '';
				for(var i=0;i<iLength-1;i++){
					sInnerHtml += '0';
				}
				// _data.text(sInnerHtml);//页面初始数字占位

				
				
			//}
		});
		// setTimeout(function(){
		// 	NumerBeat.init();
		// },500);
		NumerBeat.init();

	}
	
	$.extend({
		"numberMove": numberMove
	});
	
})(jQuery);