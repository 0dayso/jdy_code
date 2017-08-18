@extends('wap.common.wapBase')

@section('title', '风险评估')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
<style type="text/css">
.questionnaire-wrap{padding-bottom: 0.5rem;}
.questionnaire-explain{padding:0.4rem 0.75rem 0.4rem;background: #fff;}
.questionnaire-explain p{margin:0 auto 0;font-size: 0.6rem;color: #000;line-height: 0.85rem;}
.questionnaire-explain p:nth-child(1){color:#f55832;}
.issue .cnt{background:#fff;display: none;}
.issue h3{font-size:0.85rem; color:#000;height:3.6rem;line-height: 1.1;border-bottom: 1px solid #f5f5f5;padding: 0 0.75rem;display:flex;align-items: center;display:-webkit-box;-webkit-box-align: center;box-sizing: border-box;border-top:  0.4rem solid #f5f5f5;font-weight: bold;}
.issue ul{ padding:4px 10px;clear: both;}
.issue li{ height: 3.2rem;border-bottom: 1px solid #f5f5f5;vertical-align: middle;display:flex;align-items: center;display:-webkit-box;-webkit-box-align: center;box-sizing: border-box;}
.issue li:last-child{border-bottom: none;}
.issue li input{ -webkit-appearance: none;appearance: none;display:inline-block;position: relative;width: 20px;height: 20px;
    border-radius: 50%;background: #fff;border: 1px solid #a7a7a7;outline: none;color: #fff;cursor: pointer;margin:0 0.4rem 0 0.27rem;}
.issue li input:checked {background-color: #46aeff;border: none;}
.issue li input:checked::after {content: "";position: absolute;left: 5px;top: 5px;width: 10px;height: 10px;background: #fff;border-radius: 50%;}
.issue li span{position: absolute;left:0;}
.issue li label{position:relative;display:block;width:100%;color:#595959; font-size:0.75rem; line-height: 1.2;padding-left: 1.6rem;box-sizing: border-box;}
.questionnaire-bottom{clear: both;padding-left:0.75rem;margin:0.6rem 3px;color:#000000;font-size: 0.6rem;height: 1.4rem;}
.prev{ float:left;}
.ptip{ float:right;padding-right:0.75rem; }
.questionnaire-btn{display:block;margin:0.2rem auto;width:92%;height: 2.6rem;line-height:2.6rem;background: #46aeff;border-radius: 0.25rem;text-align: center;color: #fff;font-size: 1rem;border:0;box-sizing: border-box;}
.questionnaire-btn.disabled,.questionnaire-btn:disabled{background: #ccc;}
.questionnaire-mask{position: fixed;left: 0;top: 0;z-index: 5;width:100%;height:100%;background: rgba(0,0,0,0.7);}

.questionnaire-pop{position:fixed;left:50%;top:50%;z-index:10;transform: translate(-50%,-50%);-webkit-transform: translate(-50%,-50%);width:14.85rem;background: #fff;border-radius: 0.125rem;overflow: hidden;}
.questionnaire-pop-head{position:relative;height: 2.175rem;line-height:2.175rem;background: #62b6fc;text-align: center;font-size: 0.8rem;color:#fff;}
.questionnaire-pop-head h6{font-size: 0.8rem;}
.questionnaire-pop-body{padding-top:0.6rem;text-align: center;line-height: 1.55rem;font-size: 0.6rem;color:#999;}
.questionnaire-pop-btn, .questionnaire-pop-btn:link,.questionnaire-pop-btn:visited,.questionnaire-pop-btn:hover,.questionnaire-pop-btn:active{display: block;margin:0.6rem auto;width:9.0rem;height: 1.65rem;line-height: 1.65rem;background: #62b6fc;text-align: center;color:#fff;font-size: 0.75rem;border-radius: 0.125rem;border:0;}

</style>
@endsection

@section('content')

    <article class="questionnaire-wrap">


        <div class="issue" id="issue" system="{{ $system }}">
            <input name="token" type="hidden" value="{{ csrf_token() }}">
            <div class="cnt">
               <section class="questionnaire-explain">
                    <!-- <p>风险测试问卷能够帮助投资者准确的对自我风险承受能力，投资理念，投资性格等进行专业的认知测试，综合评估您的风险承受能力高低，是投资人进行投资理财之前重要的准备工作。</p> -->
                    <p>本测评表涉及内容仅供九斗鱼平台评估投资者风险承受能力，为客户提供适当的产品和服务时使用，九斗鱼平台将履行保密义务。请认真选择以下问题：</p>
                </section>

                <h3>1. 您的年龄在以下哪个范围内？</h3>

                <ul>

                    <li><label><span><input type="radio" name="is0" value="a" /></span>29岁以下</label></li>
                    <li><label><span><input type="radio" name="is0" value="b" /></span>30-39岁</label></li>
                    <li><label><span><input type="radio" name="is0" value="c" /></span>40-49岁</label></li>
                    <li><label><span><input type="radio" name="is0" value="d" /></span>50-59岁</label></li>
                    <li><label><span><input type="radio" name="is0" value="e" /></span>60岁以上</label></li>

                </ul>

            </div>


            <div class="cnt">

                <h3>2. 您有过几年的投资经验？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is1" value="a" /></span>10年以上</label></li>
                    <li><label><span><input type="radio" name="is1" value="b" /></span>6-10年</label></li>
                    <li><label><span><input type="radio" name="is1" value="c" /></span>3-5年</label></li>
                    <li><label><span><input type="radio" name="is1" value="d" /></span>1-2年</label></li>
                    <li><label><span><input type="radio" name="is1" value="e" /></span>1年以下</label></li>
                </ul>

            </div>

            <div class="cnt">

                <h3>3. 您是否有过投资经验？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is2" value="a" /></span>有投资贵金属、外汇、期货、期权等高风险衍生品经验 </label></li>
                    <li><label><span><input type="radio" name="is2" value="b" /></span>有投资股票、股票型基金的经验</label></li>
                    <li><label><span><input type="radio" name="is2" value="c" /></span>有购买过银行的理财产品、债券基金、分红型、投连险</label></li>
                    <li><label><span><input type="radio" name="is2" value="d" /></span>有购买过保本基金、货币基金（如余额宝）、信托等低风险产品</label></li>
                    <li><label><span><input type="radio" name="is2" value="e" /></span>从未有过投资经历，只存银行的定期或活期 </label></li>
                </ul>

            </div>



            <div class="cnt">

                <h3>4. 您的家庭目前全年收入状况如何？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is3" value="a" /></span>50万元以上</label></li>
                    <li><label><span><input type="radio" name="is3" value="b" /></span>30-50万元</label></li>
                    <li><label><span><input type="radio" name="is3" value="c" /></span>15-30万元</label></li>
                    <li><label><span><input type="radio" name="is3" value="d" /></span>5-15万元</label></li>
                    <li><label><span><input type="radio" name="is3" value="e" /></span>5万元以下</label></li>
                </ul>

            </div>

            <div class="cnt">

                <h3>5. 您投资的主要目的是什么？选择最符合您的一个描述：</h3>

                <ul>
                    <li><label><span><input type="radio" name="is4" value="a" /></span>关心长期的高回报，能够接受短期的资产价值波动</label></li>
                    <li><label><span><input type="radio" name="is4" value="b" /></span>倾向长期的成长，较少关心短期的回报以及波动</label></li>
                    <li><label><span><input type="radio" name="is4" value="c" /></span>希望投资能获得一定的增值，同时获得波动适度的年回报</label></li>
                    <li><label><span><input type="radio" name="is4" value="d" /></span>只想确保资产的安全性，同时希望能够得到固定的收益</label></li>
                    <li><label><span><input type="radio" name="is4" value="e" /></span>希望利用投资以及投资所获得的收益在短期内用于大额的购买计划</label></li>
                </ul>

            </div>

            <div class="cnt">

                <h3>6. 当您进行投资时(例如基金、股票)，您能接受一年内损失多少？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is5" value="a" /></span>我能承受25％以上亏损</label></li>
                    <li><label><span><input type="radio" name="is5" value="b" /></span>我能承受10-20％的亏损</label></li>
                    <li><label><span><input type="radio" name="is5" value="c" /></span>我最多只能承受5-10%的亏损  </label></li>
                    <li><label><span><input type="radio" name="is5" value="d" /></span>我最多只能承受5%以下的亏损</label></li>
                    <li><label><span><input type="radio" name="is5" value="e" /></span>我几乎不能承受任何亏损</label></li>
                </ul>

             </div>

             <div class="cnt">

                <h3>7. 目前您的投资主要是哪一品种？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is6" value="a" /></span>外汇、期货、现货贵金属 等超高风险资产</label></li>
                    <li><label><span><input type="radio" name="is6" value="b" /></span>股票、股票基金、私募股权基金 等高风险资产</label></li>
                    <li><label><span><input type="radio" name="is6" value="c" /></span>混合基金、指数基金、结构类产品 等较高风险资产 </label></li>
                    <li><label><span><input type="radio" name="is6" value="d" /></span>银行理财产品、信托、固定收益类基金(有限合
伙基金)、货币基金(如余额宝）</label></li>
                    <li><label><span><input type="radio" name="is6" value="e" /></span>活期、定期存款、国债、保险</label></li>
                </ul>

             </div>

             <div class="cnt">

                <h3>8. 如果您拥有50万用来建立资产组合，您会选择下面哪一个组合？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is7" value="a" /></span>低风险投资、一般风险投资、高风险投资的比重分别为 5:15:80</label></li>
                    <li><label><span><input type="radio" name="is7" value="b" /></span>低风险投资、一般风险投资、高风险投资的比
重分别为 10:30:60</label></li>
                    <li><label><span><input type="radio" name="is7" value="c" /></span>低风险投资、一般风险投资、高风险投资的比
重分别为 30:40:30 </label></li>
                    <li><label><span><input type="radio" name="is7" value="d" /></span>低风险投资、一般风险投资、高风险投资的比
重分别为 60:30:10</label></li>
                    <li><label><span><input type="radio" name="is7" value="e" /></span>低风险投资、一般风险投资、高风险投资的比
重分别为 80:15:5</label></li>
                </ul>

             </div>

             <div class="cnt">

                <h3>9. 长期风险承受水平：下面哪一种描述最符合您可接受的价值波动程度？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is8" value="a" /></span>希望赚取最高回报潜力，能接受3年以上的负
面波动，包括损失本金</label></li>
                    <li><label><span><input type="radio" name="is8" value="b" /></span>希望赚取较高回报潜力，能接受3年以上的负
面波动</label></li>
                    <li><label><span><input type="radio" name="is8" value="c" /></span>寻求资金较高收益，可接受3年内负面波动，
使回报显著高于定期存款</label></li>
                    <li><label><span><input type="radio" name="is8" value="d" /></span>保守投资，但愿意在2年内接受少许负面波动，
使回报高于定期存款</label></li>
                    <li><label><span><input type="radio" name="is8" value="e" /></span>不希望投资本金承担风险。我愿意接受的回报
大约与定期存款一样</label></li>
                </ul>

             </div>

             <div class="cnt">

                <h3>10. 假设现有以下几个投资品种，那您会选择哪一个？</h3>

                <ul>
                    <li><label><span><input type="radio" name="is9" value="a" /></span>收益率在30%以上，同时本金也有可能亏
损20%以上</label></li>
                    <li><label><span><input type="radio" name="is9" value="b" /></span>本金不保证，收益率在20%以内，同时本
金也有可能亏损10%以内</label></li>
                    <li><label><span><input type="radio" name="is9" value="c" /></span>本息保证，收益率在10%左右  </label></li>
                    <li><label><span><input type="radio" name="is9" value="d" /></span>本息保证，收益率在4-6%之间</label></li>
                    <li><label><span><input type="radio" name="is9" value="e" /></span>银行定期存款，收益率在3%</label></li>
                </ul>
             </div>



            <div class="questionnaire-bottom">
                    <span title="Previous" class="prev" id="prev">上一题</span>
                    <div class="ptip" id="tips"></div>
            </div>

            <a href="javascript:;" class="questionnaire-btn" id="doSend" style="display: none;">确认提交</a>

        </div>
<input type="hidden" id="touch">
    </article>
 <div class="questionnaire-comm-layer" data-modul="modul4" style="display: none;">
        <div class="questionnaire-mask" data-toggle="mask" data-target="modul4"></div>
        <div class="questionnaire-pop">
            <div class="questionnaire-pop-head">
                <h6>评估提交成功</h6>
            </div>
            <div class="questionnaire-pop-body">
                <p>亲爱的九斗鱼用户，根据风险评估结果，<br>您的风险承受能力为：</p>
                <p><h3 id="assessmentType" style="color:#62b6fc;"></h3></p>
                <p><a href="javascript:;" id="beginInvest" class="questionnaire-pop-btn">立即出借</a></p>
            </div>
        </div>
    </div>
@endsection
@section('jsScript')

<script type="text/javascript">


$(document).ready(function(){
    var i=0;
    var sld=0;
    var res=0;
    var len=$("#issue").find("div.cnt").length;
    var prog=100;
    var ht=$(".cnt").height();
    var speed=500;
    var page="data/result.html";

    function setporogress(j){
        i+=j;
        i=(i<0)? 0:i;
        $("#tips").html((i+1>len?len:i+1)+"\/"+len);

        setTimeout(function(){

            $(".cnt").hide();
            $(".cnt")[i].style.display="block";

        },200);
    }



    $("#prev").click(function(){
        if(i>0) {
            setporogress(-1);
            $(".questionnaire-btn").hide();
        }
    })

    $("#issue").find("input").click(function(){

        sessionStorage.setItem(i,$(this).val());

        if(i==len-1){

          $(".questionnaire-btn").show();

        }else{
            setporogress(1);
        }
    })

    setporogress(i);

    $("#doSend").click(function(){
        $(this).addClass('disabled');

        var dt1  = sessionStorage.getItem("0");
        var dt2  = sessionStorage.getItem("1");
        var dt3  = sessionStorage.getItem("2");
        var dt4  = sessionStorage.getItem("3");
        var dt5  = sessionStorage.getItem("4");
        var dt6  = sessionStorage.getItem("5");
        var dt7  = sessionStorage.getItem("6");
        var dt8  = sessionStorage.getItem("7");
        var dt9  = sessionStorage.getItem("8");
        var dt10 = sessionStorage.getItem("9");
        var token = $("input[name=token]").val();
        var sendData = {
            'question1':dt1,
            'question2':dt2,
            'question3':dt3,
            'question4':dt4,
            'question5':dt5,
            'question6':dt6,
            'question7':dt7,
            'question8':dt8,
            'question9':dt9,
            'question10':dt10,
            '_token':token
        };

        $.ajax({
            url:'/article/doQuestionnaire',
            type:'POST',
            dataType:'json',
            data:sendData,
            success:function(res){
                if(res.status){
                    $("#doSend").removeClass('disabled');
                    $("#assessmentType").html(res.data);
                    $(".questionnaire-comm-layer").css('display','block');

                    setTimeout(function(){
                        $("#touch").trigger('click');
                    },100)
                }
            }
        })
    })
});
    $("#touch").on('click',function(){
        var system = $("#issue").attr('system');
        var type = $("#assessmentType").html();

        if(system == 'android'){
            window.jiudouyu.setAssessmentType(type);
        }else if(system == 'ios'){
            window.location.href="objc:setAssessmentType";
        }
    });

    $("#beginInvest").on('click',function(){
        var system = $("#issue").attr('system');

        if(system == 'android'){
            window.jiudouyu.beginInvest();
        }else if(system == 'ios'){
            window.location.href="objc:beginInvest";
        }
    });

    var evclick = "ontouchend" in window ? "touchend" : "click";

    $(document).on(evclick, '[data-toggle="mask"]', function (event) {
        event.stopPropagation();
        var target = $(this).attr("data-target");
        $("div[data-modul="+target+"]").hide();

        $('[data-touch="false"]').css('pointer-events', 'none');
        setTimeout(function(){
            $('[data-touch="false"]').css('pointer-events', 'auto');
        }, 400);
     })

        //禁止鼠标穿透底层
        $('[data-touch="false"]').css('pointer-events', 'none');
        setTimeout(function(){
            $('[data-touch="false"]').css('pointer-events', 'auto');
        }, 400);
</script>

@endsection
