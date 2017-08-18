@extends('pc.common.layout')
@section('title', $title)
@section('keywords', $keywords)
@section('description', $description)
@section('content')
    @include('pc.about.common.menu')
    <div class="t-wrap t-branch">
        <h4>分支机构</h4>
        <h5>BRANCH</h5>
        <div class="t-ys-line"></div>
        <div class="clear"></div>
        <div class="branch-center">
            <h6>耀盛中国战略分布图</h6>
            <p class="branch-text">耀盛中国控股集团（九斗鱼为耀盛中国旗下互联网金融业务板块）致力于成为输出中国中小企业金融服务的完整平台，从根本上解决中国中小企业融资难、融资贵的问题，同时也让亿万普通百姓能够分享中小企业发展的经营成果。目前，我们已经在北京、深圳、广州、成都、泉州、杭州、温州、武汉、济南（如下图）设有分公司，欢迎各界朋友、出借人、中小企业家莅临指导与交流。</p>
            <p class="branch-text"></p>
            <div class="branch-map">
                <div class="ripple"></div>
                <a id="branch-logo" class="branch-logo"> <div ></div></a>
                <div class="ripple ripple1"></div>
                <a id="branch-logo1" class="branch-logo1"><div></div></a>
                <div class="ripple ripple2"></div>
                <a id="branch-logo2" class="branch-logo2"><div></div></a>
                <!--<div class="ripple ripple3"></div>-->
                <!--<a id="branch-logo3" class="branch-logo3"><div></div></a>-->
                <div class="ripple ripple4"></div>
                <a id="branch-logo4" class="branch-logo4"><div></div></a>
                <div class="ripple ripple5"></div>
                <a id="branch-logo5" class="branch-logo5"><div></div></a>
                <div class="ripple ripple6"></div>
                <a id="branch-logo6" class="branch-logo6"><div></div></a>
                <!--<div class="ripple ripple7"></div>-->
                <!--<a id="branch-logo7" class="branch-logo7"><div></div></a>-->
                <div class="ripple ripple8"></div>
                <a id="branch-logo8" class="branch-logo8"><div></div></a>
                <div class="branch-promp" id="branch-promp0" style="display:block;">
                    <div class="branch-icon2"></div>
                    <h5>北京</h5>
                    <p>耀盛中国总部</p>
                </div>
                <div class="branch-promp"  id="branch-promp1">
                    <div class="branch-icon2"></div>
                    <h5>四川省</h5>
                    <p>成都分公司</p>
                </div>
                <div class="branch-promp"  id="branch-promp2">
                    <div class="branch-icon2"></div>
                    <h5>湖北省</h5>
                    <p>武汉分公司</p>
                </div>

                <!--<div class="branch-promp"  id="branch-promp3">-->
                <!--<div class="branch-icon2"></div>-->
                <!--<h5>江西省</h5>-->
                <!--<p>南昌分公司</p>-->
                <!--</div>-->

                <div class="branch-promp"  id="branch-promp4">
                    <div class="branch-icon2"></div>
                    <h5>浙江省</h5>
                    <p>杭州分公司</p>
                    <p>温州分公司</p>
                </div>

                <div class="branch-promp"  id="branch-promp5">
                    <div class="branch-icon2"></div>
                    <h5>福建省</h5>
                    <p>泉州分公司</p>
                </div>

                <div class="branch-promp"  id="branch-promp6">
                    <div class="branch-icon2"></div>
                    <h5>广东省</h5>
                    <!--<p>东莞分公司</p>-->
                    <p>广州分公司</p>
                    <p>深圳分公司</p>
                </div>

                <!--<div class="branch-promp"  id="branch-promp7">-->
                <!--<div class="branch-icon2"></div>-->
                <!--<h5>江苏省</h5>-->
                <!--<p>南京分公司</p>-->
                <!--</div>-->

                <div class="branch-promp"  id="branch-promp8">
                    <div class="branch-icon2"></div>
                    <h5>山东省</h5>
                    <p>济南分公司</p>
                </div>

            </div>
            <!--<img src="images/new/branch-icon.png" width="38" height="15" />-->
        </div>




        <div class="branch-adress">

            <div class="box1" id="0" style="display:block;">
                <h4>北京</h4>
                <ul class="branch-button">
                    <li  class="active mr0px"><a href="#">耀盛中国总部</a></li>
                </ul>
                <p> 公司地址：北京市朝阳区建国路118号招商局大厦28层</p>
                <p> 公司电话：010-85215367</p>
                <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-bj0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-bj1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-bj2.jpg')}}" width="308" height="307" class="mr0px"/></div>
            </div>


            <div class="box1" id="1" >
                <h4>四川省</h4>
                <ul class="branch-button">
                    <li class="active mr0px"><a href="#">成都分公司</a></li>
                </ul>
                <p> 公司地址：四川省成都市武侯区人民南路四段27商鼎国际1栋1单元505</p>
                <p> 公司电话：028-87651838</p>
                <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-cd0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-cd1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-cd2.jpg')}}" width="308" height="307" class="mr0px"/></div>
            </div>


            <div class="box1" id="2">
                <h4>湖北省</h4>
                <ul class="branch-button">
                    <li  class="active mr0px"><a href="#">武汉分公司</a></li>
                </ul>
                <p> 公司地址：湖北省武汉市江汉区解放大道688号武汉广场写字楼大厦24楼2408</p>
                <p> 公司电话：027-59809690&nbsp;&nbsp;027-59809691</p>
                <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-wh0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-wh1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-wh2.jpg')}}" width="308" height="307" class="mr0px"/></div>
            </div>




            <div class="box1" id="4">
                <h4>浙江省</h4>
                <ul class="branch-button" id="branch-button1">
                    <li class="active"><a href="#">杭州分公司</a></li>
                    <li class="mr0px"><a href="#">温州分公司</a></li>
                </ul>



                <div id="notice_box1">
                    <div>
                        <p> 公司地址：浙江省杭州市拱墅区登云路518号西城时代3幢1116</p>
                        <p> 公司电话：0571-28997266</p>
                        <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-hz0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-hz1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-hz2.jpg')}}" width="308" height="307" class="mr0px"/></div>
                    </div>

                    <div style="display:none;">
                        <p> 公司地址：浙江省温州市鹿城区新城大道345号发展大厦8楼B室</p>
                        <p> 公司电话：0577-86002177</p>
                        <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-wz0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-wz1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-wz2.jpg')}}" width="308" height="307" class="mr0px"/></div>
                    </div>
                </div>
            </div>


            <div class="box1" id="5">
                <h4>福建省</h4>
                <ul class="branch-button">
                    <li  class="active mr0px"><a href="#">泉州分公司</a></li>
                </ul>
                <p> 公司地址：福建省泉州市丰泽区宝洲路689号浦西万达广场甲B2108-2109</p>
                <p> 公司电话：0595-22800889</p>
                <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-qz0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-qz1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-qz2.jpg')}}" width="308" height="307" class="mr0px"/></div>
            </div>



            <div class="box1" id="6">
                <h4>广东省</h4>
                <ul class="branch-button" id="branch-button">
                    <!--<li><a href="#">东莞分公司</a></li>-->
                    <li class="active"><a href="#">广州分公司</a></li>
                    <li class="mr0px"><a href="#">深圳分公司</a></li>
                </ul>
                <div id="notice_box">

                    <div>
                        <p> 公司地址：广东省广州市天河区黄埔大道西路76号富力盈隆大厦1615-1616</p>
                        <p> 公司电话：020-38550642</p>
                        <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-gz0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-gz1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-gz2.jpg')}}" width="308" height="307" class="mr0px"/></div>
                    </div>

                    <div  style="display:none;">
                        <p> 公司地址：深圳市福田区金田路2030号卓越世纪中心A座2608</p>
                        <p> 公司电话：0755-82909782</p>
                        <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-sz0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-sz1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-sz2.jpg')}}" width="308" height="307" class="mr0px"/></div>
                    </div>
                </div>
            </div>




            <div class="box1" id="8">
                <h4>山东省</h4>
                <ul class="branch-button">
                    <li  class="active mr0px"><a href="#">济南分公司</a></li>
                </ul>
                <p> 公司地址：山东省济南市历下区泉城路180号齐鲁国际大厦c609 </p>
                <p> 公司电话：0531-55623578</p>
                <div class="branch-photo"><img src="{{assetUrlByCdn('/static/images/new/branch-jn0.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-jn1.jpg')}}" width="308" height="307" /><img src="{{assetUrlByCdn('/static/images/new/branch-jn2.jpg')}}" width="308" height="307" class="mr0px"/></div>
            </div>

        </div>


    </div>
@endsection
@section('jspage')

@endsection