(function($){
	$(function(){
		// current question toggle
		$(".pp2-current-question").eq(0).find('dd').show();
		$(".pp2-current-question dt").each(function() {
			$(this).click(function() {
				var $par = $(this).parent("dl");
				var $bro = $(this).next("dd")
				var $arrow = $(this).find("span");
				if($par.hasClass("on")){
					$par.removeClass("on");
					$bro.hide();
					$arrow.removeClass("rotate");
					$arrow.addClass("rotatedown");
				}else{
					$par.addClass("on");
					$bro.show();
					$arrow.addClass("rotate")
					$arrow.removeClass("rotatedown")

				}

			});
		});
	});
})(jQuery)