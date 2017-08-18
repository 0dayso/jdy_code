// $(id)中数字从 data-default 滚动到 data-goal ，最后变成 data-final及数字闪烁效果

function change(id){
	var intervalSecond = 0;
	var $val = parseInt($(id).attr("data-default"));	//7
	var $goal = parseInt($(id).attr("data-goal"));	//15
	var $final = $(id).attr("data-final");	//?
	var $curr  = $val;
	var $txt = "点我";
	var intervalTimer = '';
	var resetIntervalSecond = function() {
		intervalSecond = 600;
	}
	//初始化
	resetIntervalSecond();
	//闪烁
	var flash = function(id) {
		clearInterval(intervalTimer);
		setTimeout(function(){
			$(id).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
			innerCycle();
		}, 1000);
	}
	//大循环
	var bigCycle = function(id) {
		setTimeout(function(){
			change(id);
		}, 2000);
	}
	//小循环
	var innerCycle = function() {
		intervalTimer = setInterval(function(){
			if($curr <= $goal) {
				if($curr == $val || $curr == $goal) {
					if($curr == $goal) {
						resetIntervalSecond();
					}
					flash(id);
				} else {
					clearInterval(intervalTimer);
					intervalSecond -= 100;
					intervalSecond = Math.max(150, intervalSecond);
					innerCycle();	
				}
				$(id).text($curr++);
			} else {
				clearInterval(intervalTimer);
				$(id).text($final);
				bigCycle(id);
			}
		}, intervalSecond);
	}
	
	//循环 7-15
	innerCycle();
}

