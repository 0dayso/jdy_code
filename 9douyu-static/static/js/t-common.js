 $(function(){

	var $div_li =$(".t-footer-14 li");
	$div_li.click(function(){
		$(this).addClass("selected")            //当前<li>元素高亮
			   .siblings().removeClass("selected");  //去掉其它同辈<li>元素的高亮
		$div_li.children().not("a").removeClass("t-footer-blue")
	   $(this).children().not("a").addClass("t-footer-blue")
		var index =  $div_li.index(this);  // 获取当前点击的<li>元素 在 全部li元素中的索引。
		$(".t-footer-13 > div")       //选取子节点。不选取子节点的话，会引起错误。如果里面还有div
				.eq(index).show()   //显示 <li>元素对应的<div>元素
				.siblings().hide(); //隐藏其它几个同辈的<div>元素
	})
			
	$('.t-top').click(function(){$('html,body').animate({scrollTop: '0px'},800);return false;});	
	
	//login
	$(".js_login-input").focus(function(){
		$(this).next(".icon-login").addClass("on");
	}).blur(function(){
		$(this).next(".icon-login").removeClass("on");
	});
	
	
	//分支地图
	hover(".branch-logo","#branch-promp0");
	hover(".branch-logo1","#branch-promp1")
	hover(".branch-logo2","#branch-promp2")
	hover(".branch-logo3","#branch-promp3")
	hover(".branch-logo4","#branch-promp4")
	hover(".branch-logo5","#branch-promp5")
	hover(".branch-logo6","#branch-promp6")
  	hover(".branch-logo7","#branch-promp7")
  	hover(".branch-logo8","#branch-promp8")
	function hover(a,b){
		$(a).hover(function(){/*$(b).fadeIn()},function(){$(b).fadeOut()*/
			$(".branch-promp").hide()
	   		$(b).show();
		})	
	}

	tab1("#branch-button li","#notice_box > div");
  	tab1("#branch-button1 li","#notice_box1 > div");
	tab1(".branch-map a",".box1");
	function tab1(a,b){
		 var $div_li =$(a);
        	$div_li.click(function(event){
			event.preventDefault(); 
            $(this).addClass("active")            
                   .siblings().removeClass("active"); 
            var index =  $div_li.index(this);  
			$(b) /*.eq(index).show()  
                    .siblings().hide();  */    
                    .eq(index).fadeIn(1000)  
                    .siblings().fadeOut(0); 
        })
   }
   scrollTop("#branch-logo","#0","#branch-promp0")
   scrollTop("#branch-logo1","#1","#branch-promp1")
   scrollTop("#branch-logo2","#2","#branch-promp2")
   scrollTop("#branch-logo3","#3","#branch-promp3")
   scrollTop("#branch-logo4","#4","#branch-promp4")
   scrollTop("#branch-logo5","#5","#branch-promp5")
   scrollTop("#branch-logo6","#6","#branch-promp6")
   scrollTop("#branch-logo7","#7","#branch-promp7")
   scrollTop("#branch-logo8","#8","#branch-promp8")
   function scrollTop(a,b,c){
	   $(a).click(function(){
	   $("html,body").animate({scrollTop: $(b).offset().top},1000);
	})
  }






})