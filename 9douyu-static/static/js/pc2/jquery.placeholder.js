(function($){
    $.isPlaceholder = function(){  
        var input = document.createElement('input');  
        return 'placeholder' in input;  
    }
    $.fn.iePlaceholder = function(){
        if ( !$.isPlaceholder()){
            if(!$(this).is("input[type='password']")){
                if($(this).val()=="" && $(this).attr("placeholder")!=""){
                    $(this).val($(this).attr("placeholder"));  
                    $(this).focus(function(){  
                        if($(this).val()==$(this).attr("placeholder")) $(this).val("");  
                    }).blur(function(){  
                        if($(this).val()=="") $(this).val($(this).attr("placeholder"));  
                    });  

                }
            
            }else{
                var pwdVal = $(this).attr("placeholder");
                var passwordText = '<input class="pwdPlaceholder" type="text" value='+pwdVal+' autocomplete="off" />';
                $(this).after(passwordText);
                $(this).hide();
                var thisinput = $(this);
                $(this).siblings(".pwdPlaceholder").show().css({"border":"none"}).focus(function(){
                    $(this).hide();
                    thisinput.show().focus();
                });
                thisinput.blur(function(){
                    if(thisinput.val()==''){
                        thisinput.hide();
                        $(this).siblings(".pwdPlaceholder").show();
                    }
                });
            }
        }
    }
})(jQuery);