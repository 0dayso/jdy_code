 (function($){
 	$(document).ready(function(){
 		//验证表单

 		//错误样式显示
 		function changeClass(aid , cid){
 			if(aid == 1){
 				$(cid).removeClass('financing-tips error');
 				$(cid).addClass('financing-tips');
 				$(cid).html();
 			}else{
 				$(cid).addClass("financing-tips error");
 			}
 		}
 		//selec行为判定
 		function checkBindBySelect(act ,  id , msg){
 			$(id).bind(act , function(){
 				var val = parseInt($(this).val());
 				var err = 1;
 				var cid = id+'Err';

 				if(val == 0){
 					err = 2;
 					$(cid).html(msg);
 				}
				changeClass(err, cid);
 			});
 		}

 		//验证是否有值或大于0
 		function checkeSubmit(id,type,msg){ //type 1int2string  
 			var err = 1;
 			var cid = id+'Err';
 			if(type == 1){ //int
 				var val = parseInt($(id).val());
 				if(val.length == 0 || isNaN(val) || val== 0){
 					err = 2;
 					$(cid).html(msg);
 					changeClass(err, cid);
 					return false;
 				}
 			}
 				
	       if(type == 3){
	        var val = parseFloat($(id).val());
	        if(val.length == 0 || isNaN(val) || val== 0){
	          err = 2;
	          $(cid).html(msg);
	          changeClass(err, cid);
	          return false;
	        }
	       }
 		   if(type == 2){ //str
 				var val = $(id).val();
 				if(val == '' || val== null || val == 'undefined' ||parseInt(val).length == 0 ){
 					err = 2;
 					$(cid).html(msg);
 					changeClass(err, cid);
 					return false;
 				}
 			}
 				
 			changeClass(err, cid);
 			return val;
  		}
 		//填入数字做判断
 		function checkBindByInt(act , id ){
 			$(id).bind(act , function(){
 				var val = parseInt($(this).val());
 				var err = 1;
 				var cid = id+"Err";
 				if($(this).val().length == 0 || isNaN(val)){
 					err = 2;
			        if(cid == '#ctypeErr'){
			            //console.log(123);
			            $(cid).html('<p>请输入数字</p>');
			        }else{
			            $(cid).html('请输入数字');
			        }
 				}
 				changeClass(err, cid);
 			});
 		}
 		//检查手机 11位数字
 		function checkPhone(id){
 			var val = parseInt($(id).val());
 			var err = 1;
 			var pattern = PHONE_PATTERN;
 			if($(id).val().length == 0 || isNaN(val) || $(id).val().length != 11 || (!$(id).val().match(pattern)) ){
 				err = 2;
 				$('#phoneErr').html('请输入11位手机号码');
 				changeClass(err, "#phoneErr");
 				return false;
 			}
 			changeClass(err, "#phoneErr");
 			return val;
 		}
 		function checkCaptch(id){
 			var val = parseInt($(id).val());
 			var err = 1;
 			if($(id).val.length == 0 || isNaN(val)){
 				err = 2;
 				$('#captchErr').html("请输入正确的验证码!");
 				changeClass(err, "#captchErr");
 				return false;
 			}
 			changeClass(err, "#captchErr");
 			return val;
 				
 		}
 		$("#phone").bind('blur keyup'  , function(){
 			var val = parseInt($(this).val());
 			var err = 1;
 			var pattern = PHONE_PATTERN;
 			if($(this).val().length == 0 || isNaN(val) || $(this).val().length != 11 || (!$(this).val().match(pattern)) ){
 				err = 2;
 				$('#phoneErr').html('请输入11位手机号码');
 			}
 			changeClass(err, "#phoneErr");
 		});

 		$('#smename').bind('blur keyup' , function(){
 			var smeNum = $(this).val().length;
 			var smeErr  = 1;
 			if(smeNum == 0){
 				smeErr = 2;
 				$('#smenameErr').html('请输入企业名称');
 			}
 			if( smeNum > 0 && smeNum < 2){
 				smeErr = 2;
 				$('#smenameErr').html('公司企业名称长度不少于2');
 			}
 			changeClass(smeErr, '#smenameErr');
 		});

 		$('#people').bind('blur keyup' , function(){
 			var smeNum = $(this).val().length;
 			var smeErr  = 1;
 			if(smeNum == 0){
 				smeErr = 2;
 				$('#peopleErr').html('请输入借款人姓名');
 			}
 			if( smeNum > 0 && smeNum < 2){
 				smeErr = 2;
 				$('#peopleErr').html('姓名长度不少于2');
 			}
 			changeClass(smeErr, '#peopleErr');
 		});
 		
	    $('#ctype').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#ctypeErr').html('请输入汽车品牌及型号');
	        }
	        if( carType > 0 && carType < 3){
	          carErr = 2;
	          $('#ctypeErr').html('品牌及型号长度不少于3');
	        }
	        changeClass(carErr, '#ctypeErr');
	    });
	    
 		checkBindByInt('blur keyup' , '#captital');
 		checkBindBySelect('click change', '#turnover' , "请选择年营业额!");
 		checkBindBySelect('click change', '#address' , "请选择行政区!");
 		checkBindBySelect('click change', '#industry' , "请选择所属行业!");
 		checkBindBySelect('click change', '#period' , "请选择经营年限!");
 		checkBindBySelect('click change', '#ctime' , "请选择购车时间!");
    	checkBindBySelect('click change', '#cvalue' , "请选择购买价格!");
 		checkBindByInt('blur keyup' , '#amount');
 		checkBindByInt('blur keyup' , '#deadline');
 		checkBindByInt('blur keyup' , '#hvalue');
 		checkBindByInt('blur keyup' , '#ptime');
 		checkBindByInt('blur keyup' , '#pamount');
 		checkBindByInt('blur keyup' , '#captch');

 		$('#smeForm').submit(function(){
 			var sme = checkeSubmit('#smename',2,"请输入企业名称");
 			var captital = checkeSubmit('#captital',1,"请输入数字");
 			var turnover = checkeSubmit('#turnover',1,"请选择年营业额!");
 			var address = checkeSubmit('#address',1,"请选择行政区!");
 			var industry = checkeSubmit('#industry',1,"请选择所属行业!");
 			var period = checkeSubmit('#period' , 1 ,"请选择经营年限!");
 			var amount = checkeSubmit('#amount' , 1 ,"请输入数字!");
 			var deadline = checkeSubmit('#deadline' , 1 ,"请输入数字!");
 			var people = checkeSubmit('#people' , 2 ,"请输入借款人姓名!");
 			var phone = checkPhone('#phone');
 			var sex = $("input[name='sex']:checked").val();
 			var marriage = $("input[name='marriage']:checked").val();
 			var legal = $("input[name='legal']:checked").val();
 			var holder = $("input[name='holder']:checked").val();
 			var share = $("input[name='share']:checked").val();
 			var penalty = $("input[name='penalty']:checked").val();
 			var captch = checkCaptch('#captch');
 			var act = $('#act').val() ? $('#act').val() : 'sme';
      
	       if(act == 'houses'){
	            var value = checkeSubmit('#hvalue',3,"请输入数字");
	            var type = $("input[name='htype']:checked").val();
	            var pay = $("input[name='hptype']:checked").val();
	       }
	       if( act == 'car'){
		        var cvalue = checkeSubmit('#cvalue',1,"请选择购买价格!");
		        var ctime = checkeSubmit('#ctime',1,"请选择购买时间!");
		        var ctype = checkeSubmit('#ctype',2,"请输入汽车品牌及型号");
		        var cpay = $("input[name='cpay']:checked").val();
	        }
	        if(act == 'pos'){
	          var pdata = $("input[name='pdata']:checked").val();;
	          var ptime = checkeSubmit('#ptime',1,"请输入数字");;
	          var pamount = checkeSubmit('#pamount',3,"请输入数字");;
	         }
 			 if($('.financing-tips error').length > 0){
 				return false;
 			 }
 			 if(sme && captital && turnover && address && industry && period && amount && deadline && marriage && people && phone && sex && legal && holder && share && penalty && act){
	         if(act == 'houses' && value && type && pay){
	           var data = {'type':type,'pay':pay,'hvalue':value,'sme':sme,'captital':captital,'turnover':turnover,'address':address,'industry':industry,'period':period,'amount':amount,'deadline':deadline,'people':people,'phone':phone,'sex':sex,'marriage':marriage,'legal':legal,'holder':holder,'share':share,'penalty':penalty,'captch':captch,'act':act};
	         }else if(act == 'car' && cvalue && ctime && ctype && cpay){
	            var data = {'car_type':ctype,'car_buy_time':ctime,'car_amount':cvalue,'car_pay_type':cpay,'sme':sme,'captital':captital,'turnover':turnover,'address':address,'industry':industry,'period':period,'amount':amount,'deadline':deadline,'people':people,'phone':phone,'sex':sex,'marriage':marriage,'legal':legal,'holder':holder,'share':share,'penalty':penalty,'captch':captch,'act':act};
	         }else if(act  == 'pos' && pdata && ptime && pamount){
	            var data = {'data':pdata,'pos_amount':pamount,'pos_time':ptime,'sme':sme,'captital':captital,'turnover':turnover,'address':address,'industry':industry,'period':period,'amount':amount,'deadline':deadline,'people':people,'phone':phone,'sex':sex,'marriage':marriage,'legal':legal,'holder':holder,'share':share,'penalty':penalty,'captch':captch,'act':act};
	         }else if(act == 'sme' || act == 'eml'){
	           var data = {'sme':sme,'captital':captital,'turnover':turnover,'address':address,'industry':industry,'period':period,'amount':amount,'deadline':deadline,'people':people,'phone':phone,'sex':sex,'marriage':marriage,'legal':legal,'holder':holder,'share':share,'penalty':penalty,'captch':captch,'act':act};
	         }
 
	        if(data.length == 0){
	          return false;
	        }

	         $.ajax({
	                  url: '/credit/finance_loan/doloan',
	                  type: 'post',
	                  dataType: 'json',
	                  data: data,
	                  success: function(data){
	                    if(data.status == 1){
	                      window.location = '/credit/finance_loan/dosuccess';
	                    }else if(data.status == 2){
	                      alert(data.msg);
	                    }else if(data.status == 3){
	                      changeClass(2, "#captchErr");
	                    }
	                  }
	          });
         }
 			
 			return false;
 		});

	});
 })(jQuery);

