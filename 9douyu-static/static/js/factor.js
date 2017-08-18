 (function($){
 	$(document).ready(function(){
 		//企业名称
 		$('#name').bind('blur keyup' , function(){
 			var smeNum = $(this).val().length;
 			var smeErr  = 1;
 			if(smeNum == 0){
 				smeErr = 2;
 				$('#nameErr').html('请输入企业名称');
 			}
 			if( smeNum > 0 && smeNum < 2){
 				smeErr = 2;
 				$('#nameErr').html('公司企业名称长度不少于2');
 			}
 			changeClass(smeErr, '#nameErr');
 		});
 		//经营地址
 		$('#address').bind('blur keyup' , function(){
 			var addressNum = $(this).val().length;
 			var err = 1;
 			if(addressNum == 0){
 				err = 2;
 				$('#addressErr').html('请输入经营地址');
 			}
 			if( addressNum > 0 && addressNum < 2){
 				err = 2;
 				$('#addressErr').html('经营地址名称长度不小于2');
 			}
 			changeClass(err , '#addressErr');
 		});
 		//注册资本
 		checkBindByInt('blur keyup' , '#captital');
 		//企业联系人
 		$('#contact').bind('blur keyup' , function(){
 			var addressNum = $(this).val().length;
 			var err = 1;
 			if(addressNum == 0){
 				err = 2;
 				$('#contactErr').html('请输入企业联系人');
 			}
 			if( addressNum > 0 && addressNum < 2){
 				err = 2;
 				$('#contactErr').html('企业联系人名称长度不小于2');
 			}
 			changeClass(err , '#contactErr');
 		});
 		//企业联系人地址
 		$('#contact_address').bind('blur keyup' , function(){
 			var addressNum = $(this).val().length;
 			var err = 1;
 			if(addressNum == 0){
 				err = 2;
 				$('#contact_addressErr').html('请输入联系人地址');
 			}
 			if( addressNum > 0 && addressNum < 2){
 				err = 2;
 				$('#contact_addressErr').html('联系人地址名称长度不小于2');
 			}
 			changeClass(err , '#contact_addressErr');
 		});
 		//申请金额
 		checkBindByInt('blur keyup' , '#amount');
 		//客户名称
 		$('#people').bind('blur keyup' , function(){
 			var smeNum = $(this).val().length;
 			var smeErr  = 1;
 			if(smeNum == 0){
 				smeErr = 2;
 				$('#peopleErr').html('请输入对方客户名称');
 			}
 			if( smeNum > 0 && smeNum < 2){
 				smeErr = 2;
 				$('#peopleErr').html('名称长度不少于2');
 			}
 			changeClass(smeErr, '#peopleErr');
 		});
 		//交易产品
	    $('#product').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#productErr').html('请输入交易产品');
	        }
	        if( carType > 0 && carType < 3){
	          carErr = 2;
	          $('#productErr').html('内容长度不少于3');
	        }
	        changeClass(carErr, '#productErr');
	    });
	    //交易账款应到日期
	    $('#deadline').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#deadlineErr').html('请输入日期，格式为2014-08-15');
	        }
	        if( carType > 0 && carType < 10){
	          carErr = 2;
	          $('#deadlineErr').html('内容长度不少于10，格式为2014-08-15');
	        }
	        changeClass(carErr, '#deadlineErr');
	    });
	    //保理期限起始时间
	    $('#start').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#timeErr').html('请输入起始日期，格式为2014-08-15');
	        }
	        if( carType > 0 && carType < 10){
	          carErr = 2;
	          $('#timeErr').html('时间长度不少于10，格式为2014-08-15');
	        }
	        changeClass(carErr, '#timeErr');
	    });
	    //保理期限终止时间
	    $('#end').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#timeErr').html('请输入终止日期，格式为2014-08-15');
	        }
	        if( carType > 0 && carType < 10){
	          carErr = 2;
	          $('#timeErr').html('时间长度不少于10，格式为2014-08-15');
	        }
	        changeClass(carErr, '#timeErr');
	    });
	    //项目名称
	    $('#program').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#programErr').html('请输入项目名称');
	        }
	        if( carType > 0 && carType < 2){
	          carErr = 2;
	          $('#programErr').html('名称长度不少于22');
	        }
	        changeClass(carErr, '#programErr');
	    });
	    //项目简介
	    $('#summary').bind('blur keyup' , function(){
	      
	        var carType = $(this).val().length;
	        var carErr = 1;
	        if(carType == 0){
	          carErr = 2;
	          $('#summaryErr').html('请输入简介内容');
	        }
	        if( carType > 0 && carType < 10){
	          carErr = 2;
	          $('#summaryErr').html('内容长度不少于10');
	        }
	        changeClass(carErr, '#summaryErr');
	    });

 		checkBindByInt('blur keyup' , '#captch');

 		$('#factorForm').submit(function(){

			var sme            = checkeSubmit('#name',2,"请输入企业名称");
			var captital       = checkeSubmit('#captital',1,"请输入注册资金");
			var contact        = checkeSubmit('#contact',2,"请填写企业联系人");
			var address        = checkeSubmit('#address',2,"请输入经营地址");
			var contactAddress = checkeSubmit('#contact_address',2,"请填写联系人地址");
			var amount         = checkeSubmit('#amount' , 1 ,"请输入数字");
			var type           = $("input[name='type']:checked").val();
			var start          = $("#start").val();
			var end            = $("#end").val();
			var captch         = checkCaptch('#captch');
			var act            = $('#act').val() ? $('#act').val() : 'factor';
      		
      		if( type == null){
      			$('#typeErr').html('请选择企业类型');
      			changeClass(2, '#typeErr');
      		}
      		if(start < 10 || end < 10){
      			$('#timeErr').html('请选择保理期限');
      			changeClass(2, '#timeErr');
      		}

	        if(act == 'factor'){
				var deadline = checkeSubmit('#deadline' , 2 ,"请输入时间");
				var people   = checkeSubmit('#people' , 2 ,"请输入客户名称");
				var loan     = checkeSubmit('#loan' , 1 ,"请输入数字");
				var product  = checkeSubmit('#product' , 2 ,"请输入数字");
	        }
	        if( act == 'rent'){
		        var program = checkeSubmit('#program',2,"请输入项目名称");
		        var summary = checkeSubmit('#summary',2,"请输入项目简介");
	        }
	        
 			if($('.financing-tips error').length > 0){
 				return false;
 			}

 			if(sme && captital && contact && address && contactAddress && type && amount && start && end && act && captch){
	         	
	         	if(act == 'factor' && deadline && people && loan && product){
	           		var data = {'sme':sme,'address':address,'captital':captital,'type':type,'contact':contact,'contact_address':contactAddress,'amount':amount,'start':start,'end':end,'captch':captch,'deadline':deadline,'people':people,'loan':loan,'product':product,'act':act};
	         	}else if(act == 'rent' && program && summary){
	            	var data = {'sme':sme,'address':address,'captital':captital,'type':type,'contact':contact,'contact_address':contactAddress,'amount':amount,'start':start,'end':end,'captch':captch,'program':program,'summary':summary ,'act':act};
	         	}
 
	        	if(data.length == 0){
	          		return false;
	        	}

	         	$.ajax({
	                url: '/credit/finance_loan/doFactor',
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
 })(jQuery);

