var lottery={
    index:-1,    //当前转动到哪个位置，起点位置
    count:0,    //总共有多少个位置
    timer:0,    //setTimeout的ID，用clearTimeout清除
    speed:20,    //初始转动速度
    times:0,    //转动次数
    cycle:10,    //转动基本次数：即至少需要转动多少次再进入抽奖环节
    prize:-1,    //中奖位置
    init:function(id){
        if ( $("#"+id).find(".lottery-unit").length>0 ) {
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
        this.prize=index;
        return false;
    }
};

function roll(){
    lottery.times += 1;
    lottery.roll();//转动过程调用的是lottery的roll方法，这里是第一次调用初始化
    if ( lottery.times > lottery.cycle+10 && lottery.prize==lottery.index ) {
        lotteryEvent.showLat();
        clearTimeout(lottery.timer);
        lottery.prize=-1;
        lottery.times=0;
        click=false;
    } else {
        if (lottery.times < lottery.cycle) {
            lottery.speed -= 10;
        } else if (lottery.times == lottery.cycle) {
            var index = Math.random() * (lottery.count) | 0;
            lottery.prize = index;
        } else {
            if (lottery.times > lottery.cycle + 10 && ((lottery.prize == 0 && lottery.index == 7) || lottery.prize == lottery.index + 1)) {
                lottery.speed += 110;
            } else {
                lottery.speed += 20;
            }
        }
        if (lottery.speed < 40) {
            lottery.speed = 40;
        }
        lottery.timer = setTimeout(roll, lottery.speed);//循环调用
    }
    return false;
}
//执行抽奖的js 方法
var lotteryEvent    =   {
    grade:1,
    getLevel:function () {
        var btnObj  =   $('#btn-lottery-vip');
        var level   =   btnObj.attr('lottery-level-value');
        if( level == '' || !level ){
            level   =   this.grade;
        }
        return level;
    },
    doLottery:function(event){
        event.preventDefault();
        var btnObj  =   $('#btn-lottery-vip');
        var lock    =   btnObj.attr('lottery-lock');
        if ( lock != 'open' && lock ) {
            return false;
        }
        this.lock();
        var _token  = $("input[name='_token']").val();
        level=  this.getLevel();
        $.ajax({
            url      :"/thirdAnniversary/luckDraw",
            data     :{grade:level,_token:_token},
            dataType :'json',
            type     :'post',
            success : function(json) {
                var type    =   '';
                if( json.status == false ){
                    type    =   json.data.type ? json.data.type : '';
                    if(json.msg =='验证失败,请不要重复提交!') {
                      type='error';
                    }
                    lotteryEvent.errorLet(type,json.msg);
                    lotteryEvent.unlock();
                } else if( json.status == true ) {
                    var index   =   json.data.order_num;
                    var name    =   json.data.name;
                    lotteryEvent.successLet(name,level,index);
                    lotteryEvent.unlock();
                    roll();
                    click=true;
                    return false;
                }

            }, error : function() {
                lotteryEvent.errorLet('error','抽奖失败,请稍后重试');
                lotteryEvent.unlock();
            }
        });

    },
    successLet:function (name,grade,index) {
        var lotteryObj      =   $('#anniversary-plate-tab');
        var lotteryNumber   =   lotteryObj.attr('attr-lottery-value');
        var static_url      =   lotteryObj.attr('attr-images-static');
        var canLottery      =   lotteryNumber-1;
        if( canLottery <=0 ) {
            canLottery  =   0;
        }

        if( static_url =='' || !static_url ) {
            static_url  =   'https://img1.9douyu.com';
        }
        var images_url  =  static_url+'/static/activity/thirdanniversary/images/one-plate' + grade + '-img' + index +'.png';
        var html    =   '' +
                '<div class="anniversary-mask" data-close="layer-net"></div>'+
                '<div class="anniversary-pop" id="lottery-info">'+
                    '<span class="anniversary-pop-close" data-close="layer-net"></span>'+
                    '<p class="anniversary-pop-tip1">恭喜你获得了</p>'+
                    '<div class="anniversary-prize-main">'+
                    '<img src="'+images_url+'" alt="中奖图片" class="anniversary-prize-img"/>'+
                    '</div>'+
                    '<p>'+name+'</p>'+
                    '<p>还有<span class="anniversary-label-color">'+canLottery+'</span>次抽奖机会哦！</p>'+
                    '<a href="javascript:;" class="anniversary-btn-pop" data-close="layer-net">朕知道了</a>'+
                '</div>';
        $('.anniversary-layer1').html(html);
        lotteryObj.attr('attr-lottery-value',canLottery);
    },
    errorLet:function (type,information) {
        var btm_css     =   '<a href="javascript:;" class="anniversary-btn-pop" data-close="layer-net">知道了</a>';
        var img_css     =   '<span class="anniversary-icon-login"></span>'
        if( type == 'notLogged' ) {
            btm_css     =   '<a href="/login" class="anniversary-btn-pop" data-close="layer-net">马上登录</a>';
        }
        if( !type  || type =='' ) {
            btm_css     =   '<a href="#float-three" class="anniversary-btn-pop" data-close="layer-net">马上去投资</a>';
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
    lock:function () {
        $('#btn-lottery-vip').addClass("btn-plate-disabled").attr('lottery-lock','close');
    },
    unlock:function () {
        $('#btn-lottery-vip').removeClass("btn-plate-disabled").attr('lottery-lock','start');
    },
    showLat:function() {
        $(".anniversary-layer1").show();
    }
}
