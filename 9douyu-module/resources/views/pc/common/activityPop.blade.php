<div class="pop-a-wrap">

	@if( $activityTime['start'] > time())
	<div class="pop-mask"></div>
	<div class="pop-start">
		<span class="pop-close-a"></span>
	</div>
	@endif

	@if($activityTime['end'] < time())
	<div class="pop-mask"></div>
	<div class="pop-end">
		<span class="pop-close-a"></span>
	</div>
	@endif
</div>



