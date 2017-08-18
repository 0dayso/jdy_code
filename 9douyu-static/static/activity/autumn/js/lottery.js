var lottery={
    index:-1,    //当前转动到哪个位置，起点位置
    count:0,    //总共有多少个位置
    timer:0,    //setTimeout的ID，用clearTimeout清除
    speed:20,    //初始转动速度
    times:0,    //转动次数
    cycle:10,    //转动基本次数：即至少需要转动多少次再进入抽奖环节
    prize:-1,    //中奖位置
    init:function(id){
        if ($("#"+id).find(".lottery-unit").length>0) {
            $lottery = $("#"+id);
            $units = $lottery.find(".lottery-unit");
            this.obj = $lottery;
            this.count = $units.length;
            $lottery.find(".lottery-unit-"+this.index).addClass("active");
        };
    },
    roll:function(){
        var index = this.index;
        var count = this.count;
        var lottery = this.obj;
        $(lottery).find(".lottery-unit-"+index).removeClass("active");
        index += 1;
        if (index>count-1) {
            index = 0;
        };
        $(lottery).find(".lottery-unit-"+index).addClass("active");
        this.index=index;
        return false;
    },
    stop:function(index){
        this.prize=index
        return false;
    }
};

function roll(){
    lottery.times += 1;
    lottery.roll();//转动过程调用的是lottery的roll方法，这里是第一次调用初始化
    if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
        lotteryEvent.showLat();
        lotteryEvent.unlock();
        clearTimeout(lottery.timer);
        lottery.prize=-1;
        lottery.times=0;
        click=false;
    }else{
        if (lottery.times<lottery.cycle) {
            lottery.speed -= 10;
        }else if(lottery.times==lottery.cycle) {
            lottery.prize  =   $('#btn-lottery-vip').attr('lottery-active-prize') ;

        }else{
            if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
                lottery.speed += 110;
            }else{
                lottery.speed += 20;
            }
        }
        if (lottery.speed<40) {
            lottery.speed=40;
        };

        if( !lottery.prize ) {
            setTimeout(function () {
                lottery.prize  =   $('#btn-lottery-vip').attr('lottery-active-prize') ;
            },100)
        }
        //console.log(lottery.times+'^^^^^^'+lottery.speed+'^^^^^^^'+lottery.prize);
        lottery.timer = setTimeout(roll,lottery.speed);//循环调用
    }
    return false;
}
    //执行抽奖的js 方法
var lotteryEvent    =   {
    grade:1,
    getLevel:function (level) {
        if( !level ) {
            level   =   $('#btn-lottery-vip').attr('lottery-level-value');
        }
        return level;
    },
    doLottery:function(id,activity){
        var btnObj  =   $('#lottery-btn'+id);
        var lock    =   btnObj.attr('lottery-lock');
        if ( lock != 'start' && lock ) {
            return false;
        }
        this.lock(id);
        roll();
        var _token  = $("input[name='_token']").val();
        level=  this.getLevel(id);
        var luckUrl =   '/activity/' + activity + '/luckDraw' ;
        $.ajax({
            url      :luckUrl,
            data     :{grade:level,_token:_token},
            dataType :'json',
            type     :'post',
            success : function(json) {
                var type    =   '';
              console.log(json)
                if( json.status == false ){
                    type    =   json.data.type ? json.data.type : '';
                    if( json.msg =='验证失败,请不要重复提交!' ) {
                      type='error';
                    }
                    lotteryEvent.errorLet(type,json.msg);
                    lotteryEvent.unlock();
                    click=true;
                    return false;
                } else if( json.status == true ) {
                    var index   =   json.data.order_num-1;
                    var name    =   json.data.name;
                    type    =   json.data.type ? json.data.type : '';
                    if( type ==5 ) {
                        lotteryEvent.errorLet(type,name);
                        lotteryEvent.unlock();
                    } else  {
                        lotteryEvent.successLet(name,level,index);
                    }
                    click=true;return false
               }

            }, error : function() {
                lotteryEvent.errorLet('error','抽奖失败,请稍后重试');
                lotteryEvent.unlock();
            }
        });

    },
    successLet:function (name,grade,index) {
        var lotteryObj      =   $('#btn-lottery-vip');
        lotteryObj.attr('lottery-active-prize',index)
        var html    =   '' +
                '<div class="anniversary-mask" data-close="layer-net"></div>'+
                '<div class="anniversary-pop" id="lottery-info">'+
                    '<span class="anniversary-pop-close" data-close="layer-net"></span>'+
                    '<p class="anniversary-pop-tip">恭喜你获得了</p>'+
                    '<p class="anniversary-pop-tip2">'+name+'</p>'+
                    '<a href="javascript:;" class="anniversary-btn-pop" data-close="layer-net">朕知道了</a>';
        $('.anniversary-layer1').html(html);
    },
    errorLet:function (type,information) {
        var lotteryObj      =   $('#btn-lottery-vip');
        lotteryObj.attr('lottery-active-prize',7)
        var btm_css     =   '<a href="javascript:;" class="anniversary-btn-pop" data-close="layer-net">知道了</a>';
        var img_css     =   '<span class="anniversary-icon-login"></span>'
        if( type == 'notLogged' ) {
            btm_css     =   '<a href="/login" class="anniversary-btn-pop" data-close="layer-net">马上登录</a>';
        }
        if( !type  || type =='' ) {
            btm_css     =   '<a href="#invest-page" class="anniversary-btn-pop invest-page">马上去投资</a>';
            img_css     =   '<span class="anniversary-icon-invest"></span>'
        }
        var error_html  =   '' +
                '<div class="anniversary-mask" data-close="layer-net"></div>' +
                '<div class="anniversary-pop">' +
                    '<span class="anniversary-pop-close" data-close="layer-net"></span>' +
                    '<p class="anniversary-pop-tip1">'+information+'</p>' +
                    '<div class="anniversary-prize-wrap">' +
                        img_css +
                    '</div>' +
                    btm_css +
                '</div>';
        $('.anniversary-layer1').html(error_html);
    },
    lock:function (id) {
        $('.page-lottery-btn').attr('lottery-lock','close');
        $('#lottery-btn'+id).addClass('disable');
    },
    unlock:function () {
        $('.page-lottery-btn').removeClass("disable").attr('lottery-lock','start');
    },
    showLat:function() {
        $(".anniversary-layer1").show();
    }
}
