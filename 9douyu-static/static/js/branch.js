$(document).ready(function(){
	hover(".branch-logo","#branch-promp");
	hover(".branch-logo1","#branch-promp1")
	hover(".branch-logo2","#branch-promp2")
	hover(".branch-logo3","#branch-promp3")
	hover(".branch-logo4","#branch-promp4")
	hover(".branch-logo5","#branch-promp5")
	hover(".branch-logo6","#branch-promp6")
  hover(".branch-logo7","#branch-promp7")
  hover(".branch-logo8","#branch-promp8")
	function hover(a,b){
		$(a).hover(function(){$(b).fadeIn();$().attr("src","")},function(){$(b).fadeOut()})	
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


       scrollTop("#branch-logo","#0")
       scrollTop("#branch-logo1","#1")
       scrollTop("#branch-logo2","#2")
       scrollTop("#branch-logo3","#3")
       scrollTop("#branch-logo4","#4")
       scrollTop("#branch-logo5","#5")
        scrollTop("#branch-logo6","#6")
        scrollTop("#branch-logo7","#7")
        scrollTop("#branch-logo8","#8")
       function scrollTop(a,b){
           $(a).click(function(){
           $("html,body").animate({scrollTop: $(b).offset().top},1000);
        })
      }
   })