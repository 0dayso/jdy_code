<style type="text/css">
	.pop-mask{position: fixed; z-index: 999990; top: 0; left: 0; bottom: 0; right: 0;background:#000; opacity: 0.7; -moz-opacity:0.7;filter:alpha(opacity=70);}
	.pop-end{width:688px;  z-index: 999999;height: 446px; position:fixed; top:50%; left: 50%;background: url("{{ assetUrlByCdn('/static/activity/activity-end.png') }}") top center no-repeat;margin:-223px -344px;}
	.pop-close-a{width: 57px; height: 57px;position: fixed; z-index: 9999999; top: 100px; right: 200px; background: url("{{ assetUrlByCdn('/static/activity/activity-close.png') }}") 0 0 no-repeat; cursor: pointer; }
</style>
<div class="pop-mask"></div>
<div class="pop-end">
	<span class="pop-close-a"></span>
</div>
<script src="{{ assetUrlByCdn('/static/js/jquery-1.9.1.min.js') }}"></script>
<script type="text/javascript">
(function($){
	$(function(){
		$('.pop-close-a,.pop-mask').click(function() {
			$('.pop-mask,.pop-end').fadeOut();
		});
	})
})(jQuery)
</script>