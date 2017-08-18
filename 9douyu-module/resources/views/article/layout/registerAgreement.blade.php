<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=2.0, user-scalable=0">
	<meta name="format-detection" content="telphone=no">
	<title>{{$info['title']}}</title>
	<style>
		* { margin: 0; padding: 0;}
		*:focus {outline: none;}
		html { font-family:"微软雅黑","Helvetica Neue", Helvetica, STHeiTi, Arial, sans-serif; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; font-size: 62.5%; height: 100%; }
		body { max-width: 800px; margin: 0 auto; font-size: 1.4rem; line-height: 1.5; color: #333; background-color: #F4F4F4; height: 100%; overflow-x: hidden; -webkit-overflow-scrolling: touch; -webkit-tap-highlight-color:rgba(0,0,0,0); }
		article, aside, details, figcaption, figure, footer, header, hgroup, main, nav, section, summary { display: block; }
		audio, canvas, progress, video { display: inline-block; vertical-align: baseline; }
		audio:not([controls]) { display: none; height: 0; }
		[hidden], template { display: none; }
		svg:not(:root) { overflow: hidden; }
		a { background: transparent; text-decoration: none; -webkit-tap-highlight-color: transparent; color: #333; }
		a:active { outline: 0; color: #1D89DF; }
		li { list-style: none;}
		abbr[title] { border-bottom: 1px dotted; }
		b, strong { font-weight: bold; }
		dfn { font-style: italic; }
		mark { background: #ff0; color: #000; }
		small { font-size: 80%; }
		sub, sup { font-size: 75%; line-height: 0; position: relative; vertical-align: baseline; }
		sup { top: -0.5em; }
		sub { bottom: -0.25em; }
		img { border: 0; vertical-align: middle; max-width: 100%; }
		hr { -moz-box-sizing: content-box; box-sizing: content-box; height: 0; }
		pre { overflow: auto; white-space: pre; white-space: pre-wrap; word-wrap: break-word; }
		code, kbd, pre, samp { font-family: monospace, monospace; font-size: 1em; }
		button, input, optgroup, select, textarea { color: inherit; font: inherit; margin: 0; }
		button { overflow: visible; }
		button, select { text-transform: none; }
		button, html input[type="button"], input[type="reset"], input[type="submit"] { -webkit-appearance: button; cursor: pointer; }
		button[disabled], html input[disabled] { cursor: default; }
		button::-moz-focus-inner, input::-moz-focus-inner { border: 0; padding: 0; }
		input { line-height: normal; }
		input[type="checkbox"], input[type="radio"] { box-sizing: border-box; padding: 0; }
		input[type="number"]::-webkit-inner-spin-button, input[type="number"]::-webkit-outer-spin-button { height: auto; }
		input[type="search"] { -webkit-appearance: textfield; -moz-box-sizing: border-box; -webkit-box-sizing: border-box; box-sizing: border-box; }
		input[type="search"]::-webkit-search-cancel-button, input[type="search"]::-webkit-search-decoration { -webkit-appearance: none; }
		fieldset { border: 1px solid #c0c0c0; margin: 0 2px; padding: 0.35em 0.625em 0.75em; }
		legend { border: 0; padding: 0; }
		textarea { overflow: auto; resize: vertical; }
		optgroup { font-weight: bold; }
		table { border-collapse: collapse; border-spacing: 0; }
		td, th { padding: 0; }
		em,i { font-style: normal;}
		.red { color: #FF6464;}
		.black { color: #000;}
		.blue { color: #1E89E0;}
		.orange { color: #FF6000;}
		.gray { color: #B4B4B4;}
		.green { color: #139B23;}
		.clearfix:after {clear:both; display:block;height:0;content:"\200B";}
		.clearfix { *zoom:1; }
		.con { background: #fff; padding:0 5% 2rem 5%;}
		h1 { text-align: center; font-size: 1.6rem; color: #000;}
		h2 { font-size: 1.4rem; color: #000; text-indent: 1.8em;}
		h1,h2,p { display: block; padding-top: 1rem;}
		p { font-size: 1.3rem;text-indent: 2em; }
		@media only screen and (min-width:414px ) {
			html { font-size: 68.8%; }
		}
		@media only screen and (min-width:600px ) {
			html { font-size: 75%; }
		}
		@media only screen and (min-width:640px ) {
			html { font-size: 100%%; }
		}
		td{ height: 30px; line-height: 30px; text-align: center; border: 1px solid #ccc; }
		td p{ text-indent: 0em; }
		.table1 td{ width: 20%}
		.table2 td{ width: 25%}
		h1{font-size: 20px; text-align: center;}
		h2{font-size: 16px;}
	</style>
	<!--[if IE]>
	<style>
		html { height:auto;overflow-y: scroll;}
		body { height:auto;font-size: 14px;}
		.con { padding:0 5% 20px 5%;}
		h1 { font-size: 16px;}
		h2 { font-size: 14px; }
		h1,h2,p { padding-top: 10px;}
		p { font-size: 13px;}
	</style>
	<![endif]-->
</head>
<body>
<div class="con">
	{!! htmlspecialchars_decode($info['content']) !!}
</div>

</body>
</html>