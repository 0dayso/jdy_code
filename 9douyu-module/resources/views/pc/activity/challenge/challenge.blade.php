@extends('pc.common.layout')

@section('title', '耀盛高校金融精英挑战赛')

@section('keywords', env('META_KEYWORD'))

@section('description', env('META_DESCRIPTION'))

@section('css')
    <meta name="format-detection" content="telephone=yes">
@endsection
@section('content')
    <link rel="stylesheet" type="text/css" href="{{ assetUrlByCdn('/static/activity/challenge/css/challenge.css') }}">
    <div class="wrap">
        <!-- nav -->
        <div class="chall-nav">
            <a href="#news">
                <span>01</span>赛事动态
            </a>
            <a href="#process">
                <span>02</span> 赛事流程
            </a>
            <a href="#tutor">
                <span>03</span>导师战队
            </a>
            <a href="#info">
                <span>04</span>赛事介绍
            </a>
            <a href="#result">
                <span>05</span>赛况赛果
            </a>
        </div>
        <!-- End nav -->

        <!-- pic -->
        <div class="chall-pic">
            <div id="chall-pic-prev" class="chall-pic-prev"></div>
            <div id="chall-pic" class="chall-pic-main">
                <ul>
                    <li><img src="{{ assetUrlByCdn('/static/activity/challenge/images/pic_1.jpg') }}" /></li>
                    <li><img src="{{ assetUrlByCdn('/static/activity/challenge/images/pic_2.jpg') }}" /></li>
                    <li><img src="{{ assetUrlByCdn('/static/activity/challenge/images/pic_3.jpg') }}" /></li>
                    <li><img src="{{ assetUrlByCdn('/static/activity/challenge/images/pic_4.jpg') }}" /></li>
                    <li><img src="{{ assetUrlByCdn('/static/activity/challenge/images/pic_5.jpg') }}" /></li>
                    
                </ul>
            </div>
            <div id="chall-pic-next" class="chall-pic-next"></div>
        </div>
        <!-- End pic -->

        <!-- news -->
        <div class="chall-news">
            <a name="news"></a>

            <div class="chall-title">
                <h2>赛事动态</h2>
            </div>
            <div class="chall-news-main">
                <ul>
                    <li>
                        <span>•</span>
                        <a href="http://www.9douyu.com/article/1378" target="_blank">九斗鱼CEO郭鹏：“金融精英挑战赛”将成为一流金融科技人才的“入口”</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="https://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484576&idx=1&sn=c58efa0118d87817bb2430dfea49d003&chksm=e8e4cf88df93469ebdc01905b75233742653910b5e93dbac153e59e868f1ed5a3ce8cb5b3b9e&mpshare=1&scene=1&srcid=1118A96gFSvrstjqGfMmHcyL&pass_ticket=kMQ62" target="_blank">全国赛丨北京站 8进6胜负角逐！</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483893&idx=1&sn=83e2d624d3e0cce1cd9d84a559d57b15&mpshare=1&scene=1&srcid=1102TGVp4BsYcIy4gqhue8su#rd" target="_blank">10家金融机构offer，30个培训名额，还有7天就将结束！</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483949&idx=1&sn=5592c909ec14cf49c19a74e116c325f0&mpshare=1&scene=1&srcid=11023JJtd7cjLlcNElvJkj21#rd" target="_blank">倒计时第三天！【金融赛证书介绍】 | 认证机构：海外人才库</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484023&idx=1&sn=e59f1d960a40dcda6debb505e62ff02e&mpshare=1&scene=1&srcid=1102fx4Q3yXqtBaKlmdIgxhG#rd" target="_blank">挑战赛学术支持机构大解读</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484471&idx=1&sn=faf3d1a840cc728d4cba6bca40dac61c&chksm=e8e4cf1fdf934609156275fad41c5333e96c64eb57e555ec67a6b43aab795c9f0d75d6c69455&mpshare=1&scene=1&srcid=1102XXgEYyjTXpnhlQhSmyfF#rd" target="_blank">倒计时10小时 | 全国赛预备队伍分数首次曝光</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483774&idx=1&sn=82759c80c406f8f17ff1501a7a35a669&mpshare=1&scene=1&srcid=1102SiTa9aNBRLnTdwUqT2TT#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛宣讲丨吉林大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483926&idx=3&sn=691abeb2ca6e265ed9f1d86ba68b2553&mpshare=1&scene=1&srcid=1102q5BDFDF2Ifntyf8kcnUD#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨天津工业大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483926&idx=4&sn=516c135e1908394e75a6c17f0ce5486d&mpshare=1&scene=1&srcid=1102APjvFnlzuoOycVkhVYkS#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨南开大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483926&idx=5&sn=0bdd7d58f6feb10352eb06be5fab2fc4&mpshare=1&scene=1&srcid=1102OkXEkBp7Rn3olmhItbbM#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨南京财经大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483968&idx=3&sn=580d1532fdbb3a028dd36a3b88dd6e7c&mpshare=1&scene=1&srcid=1102HwYnJ6d9usxYC7qyJ2aZ#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨天津大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247483999&idx=2&sn=ef73b87743c8017a0a29e0e2eb0cd5c4&mpshare=1&scene=1&srcid=11029lmVnFYZzSasbIuBaJV4#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨郑州航空工业管理学院</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484033&idx=3&sn=1e72444ba2fde5115ea4f3bb0c434c29&mpshare=1&scene=1&srcid=11023fRNkCHdpRnalpIgkrf4#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨云南财经大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484033&idx=2&sn=f447f52186b07e98267766c57e3acb44&mpshare=1&scene=1&srcid=1102GzsRnmTcCZOhfCyRh8xq#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨天津工业大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484081&idx=2&sn=4c06b8b8929e46f5f847891bf1aba0fb&mpshare=1&scene=1&srcid=1102Aez7PB7xsuzJZgbb6SzR#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛进校园丨山东大学</a>
                    </li>
                    
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484428&idx=2&sn=4c475a8c2ca493d21752a6feb8651ab4&chksm=e8e4cf24df934632814f934c2f9aaf91b888d1a4b92c2d1ace750ad818207bd3a088defafb28&mpshare=1&scene=1&srcid=1102txD4qUIO6IZnHITGnlYY#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨厦门大学&集美大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484428&idx=3&sn=6773b06cd08bd1f9464586a18e318fb8&chksm=e8e4cf24df934632116bc2490f40f2ed04b9857a6c894784806ea97710532456d60585ecc90f&mpshare=1&scene=1&srcid=1102engtawjSLjjmfxSRio4p#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨对外经济贸易大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484428&idx=4&sn=d1764710115941988d7bcb3f435885b3&chksm=e8e4cf24df9346328e96f496d928f95178848979b533057ee8480eca69f1c19f6241898d5faf&mpshare=1&scene=1&srcid=11026Gm24oefvBNGT28vft0F#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨中国地质大学&湖北经济学院</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484428&idx=5&sn=3fbab349947b5d97703849033a299ce8&chksm=e8e4cf24df93463202b6a25d09bf1a0c9abc2c2bd78f6c5ec68382de9e19bf0e384ca33963aa&mpshare=1&scene=1&srcid=1102xjaaNtKDoNqOPbs4C5mV#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨河北农业大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484378&idx=2&sn=d6510fd29406b6f3c506acab5671a1c8&chksm=e8e4c8f2df9341e4a3d9bbd0535e1a9280a88a49582060b976ed33ce9a20e425671724bae9b9&mpshare=1&scene=1&srcid=1102fYhJAIHZNfYUo4MiqyEy#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨哈尔滨理工大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484378&idx=3&sn=ece757b5dae562d0961779b7b92b16fa&chksm=e8e4c8f2df9341e4aa9a7478172770f207b556fd2ce91fd04006e0c71b4a854552ec505b5bfb&mpshare=1&scene=1&srcid=1102Jc9M3xRnUL3CAGyr4VSR#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨东北财经大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484378&idx=5&sn=452ff2c5e7ca103f961c4f9444e7153b&chksm=e8e4c8f2df9341e45263643e510205712fd2438382219a4f2b9f8056c2edce3dacd94ab10f87&mpshare=1&scene=1&srcid=1102WVREc022NbCNKDuaNu2G#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨南开大学&天津财经大学</a>
                    </li>
                    
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484272&idx=1&sn=63a8ded53ec1831e8f4a8414f0d51f1a&chksm=e8e4c858df93414e521a8d9e6e02403410545ca16fff076094f61688401604e7becdb16924fb&mpshare=1&scene=1&srcid=1102MIuBMCMh6uD5YwPyvhyw#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨天津外国语&天津商业大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484248&idx=1&sn=28c640a3302a1ccdcfb30a3102cf614c&chksm=e8e4c870df9341665c6a66906c3d17b21252aa357fe72397f6fedcb0fccd4d22b8de25f3df10&mpshare=1&scene=1&srcid=1102XocB2bEmlpo77C0UJ6Hy#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨南京审计大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484248&idx=2&sn=d72db7e9ac2c2ed027a5efd18068e448&chksm=e8e4c870df934166b77f026bcfe809e70f5966660a6f7964da71988ede3d9e57391266e1fe0d&mpshare=1&scene=1&srcid=1102tDrI34WIRmmEuRPCJ0OV#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨吉林大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484248&idx=3&sn=dbeb6340cb013e6bdcca41eb46cdcb70&chksm=e8e4c870df934166f2f1d8cec698f7023c17dd1b84da4158cec0afc8944cfb5d25eee19b959c&mpshare=1&scene=1&srcid=1102G4K0qXo71D7e6iPOzCfx#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨云南大学</a>
                    </li>
                    <li>
                        <span>•</span>
                        <a href="http://mp.weixin.qq.com/s?__biz=MzIzNTU0NDEzNQ==&mid=2247484210&idx=1&sn=bc13be121f8c75f842fd70e5e909000d&chksm=e8e4c81adf93410c367f31492199322f380ec2422142f1e0a65befeda21796a10850a256a0cb&mpshare=1&scene=1&srcid=1102H5MeuCzIm7QMiGi1eVS4#rd" target="_blank">2016“蓝筹计划”金融精英挑战赛校内赛丨中央财经大学</a>
                    </li>
                    
                </ul>
            </div>
        </div>
        <!-- End news -->
        <div class="clear"></div>

        <!-- process -->
        <div class="chall-title">
            <a name="process"></a>
            <h2>赛事流程</h2>
        </div>
        <div class="chall-process">
            <ul>
                <li class="right">
                    <p>海选赛</p>
                    <p><small>（线上答题）</small></p>
                    <div class="call-process-box">
                        <p class="mt1">2016年8月20日至09月20日 ，参赛者需通过在线答题获取晋级资格。</p>
                    </div>
                </li>
                <li class="right">
                    <p>校园赛</p>
                    <p><small>（校内案例分析竞赛）</small></p>
                    <div class="call-process-box">
                        <p class="mt2">2016年09月25日至10月25日，由院校组织校内赛通过案例答辩对海选晋级者再次选拔。</p>
                    </div>
                </li>
                <li class="right">
                    <p>赛区赛</p>
                    <p><small>（线上路演能力赛）</small></p>
                    <div class="call-process-box">
                        <p class="mt3">2016年10月27日12时至11月03日16时，参赛者通过线上投票，最终以投票支持情况及校内赛晋级成绩决出30强。</p>
                    </div>
                </li>
                <li>
                    <p>全国赛</p>
                    <p><small>（实地研习会）</small></p>
                    <div class="call-process-box">
                        <p class="mt4">2016年11月05日至11月27日，全国赛共三场，分别为苏州、北京、上海，11月12日--13日耀盛中国北京站全面开赛，并邀请企业超级导师组进行全程指导和点评。</p>
                    </div>
                </li>
            </ul>
        </div>
        <!-- End process -->

        <!-- tutor -->
        <div class="chall-title">
            <a name="tutor"></a>
            <h2>导师战队</h2>
        </div>
        <div class="chall-tutor">
            <ul class="clearfix">
                <li><a href="/activity/challenge/detail#1" target="_blank">
                        <img src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor01.jpg') }}" />
                        <p><strong>郭鹏战队</strong></p>
                    </a>
                </li>
                <li><a href="/activity/challenge/detail#2" target="_blank">
                    <img src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor02.jpg') }}" />
                        <p><strong>刘丽慷战队</strong></p>
                    </a>
                </li>
                <li><a href="/activity/challenge/detail#3" target="_blank">
                    <img src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor03.jpg') }}" />
                        <p><strong>姜传震战队</strong></p>
                    </a>
                </li>
                <li><a href="/activity/challenge/detail#4" target="_blank">
                <img src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor04.jpg') }}" />
                        <p><strong>王艺筱战队</strong></p>
                    </a>
                </li>
                <li><a href="/activity/challenge/detail#5" target="_blank">
                <img src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor05.jpg') }}" />
                        <p><strong>刘凯战队</strong></p>
                    </a>
                </li>
                <li><a href="/activity/challenge/detail#6" target="_blank">
                <img src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor06.jpg') }}" />
                        <p><strong>臧克战队</strong></p>
                    </a>
                </li>
            </ul>
        </div>
        <!-- End tutor -->

        <!-- info -->
        <div class="chall-title">
            <a name="info"></a>
            <h2>赛事介绍</h2>
        </div>
        <div class="chall-info">
            <p>耀盛|中国高校金融精英挑战赛通过全国性比赛将众多知名企业对优秀人才的考核标准融入比赛内容中。大赛共分为海选赛、校内赛、赛区赛、全国赛四个阶段；分别从金融专业知识、综合职业素养、未来精英潜质等多个维度，通过线上选拔比赛、线下案例分析等环节全面考查参赛者的综合素养。经过对参赛者进行严格筛选，最终遴选并重点打造30名“金融启明星”并将于11月5日至11月27日在苏州、北京、上海三地进入最终全国赛阶段。</p>
            <p>大赛面向全国高等院校在校大学生、研究生开放。覆盖全国24个省、市、自治区、直辖市，共涉及约50余座主要城市、200余所主要财经类高校、以及上百所非金融类院校的共计20万余名在校学生参赛。</p>
            <p>本次活动不仅提供给在校财经类学子一个才能展示平台 ，同时还为财经类高校提供了与知名企业机构以及与院校之间相互交流和学习的机会。耀盛中国亦希望能借此与优秀财经类院校和企业机构建立良好的战略合作关系。</p>
        </div>
        <!-- End info -->

        <!-- result -->
        <div class="chall-title">
            <a name="result"></a>
            <h2>赛况赛果</h2>
        </div>
        <div class="chall-subtitle"><span>•</span>赛事回顾</div>
        <div class="btnMode" id="slider">    
            <a href="javascript:void(0);" class=" btnprev"></a>
            <div class="scroll">    
                <ul class="scrollContainer">
                    
                    <li class="panel" id="panel_1">
                            <img width="278" height="184" alt="导师指导" src="{{ assetUrlByCdn('/static/activity/challenge/images/img02.jpg') }}" />
                            <span>导师指导</span>
                    </li>
                    <li class="panel" id="panel_2">
                            <img width="278" height="184" alt="导师指导" src="{{ assetUrlByCdn('/static/activity/challenge/images/img03.jpg') }}" />
                            <span>导师指导</span>
                    </li>
                    <li class="panel" id="panel_3">
                            <img width="278" height="184" alt="导师指导" src="{{ assetUrlByCdn('/static/activity/challenge/images/img04.jpg') }}" />
                            <span>导师指导</span>
                    </li>
                    <li class="panel" id="panel_4">
                            <img width="278" height="184" alt="答辩准备" src="{{ assetUrlByCdn('/static/activity/challenge/images/img05.jpg') }}" />
                            <span>答辩准备</span>
                    </li>
                    <li class="panel" id="panel_5">
                            <img width="278" height="184" alt="答辩" src="{{ assetUrlByCdn('/static/activity/challenge/images/img06.jpg') }}" />
                            <span>答辩抽签</span>
                    </li>
                    <li class="panel" id="panel_6">
                            <img width="278" height="184" alt="答辩" src="{{ assetUrlByCdn('/static/activity/challenge/images/img07.jpg') }}" />
                            <span>答辩</span>
                    </li>
                    <li class="panel" id="panel_7">
                            <img width="278" height="184" alt="答辩" src="{{ assetUrlByCdn('/static/activity/challenge/images/img08.jpg') }}" />
                            <span>答辩</span>
                    </li>
                    <li class="panel" id="panel_8">
                            <img width="278" height="184" alt="抽取题目" src="{{ assetUrlByCdn('/static/activity/challenge/images/img01.jpg') }}" />
                            <span>抽取题目</span>
                    </li>
                </ul>
            </div>
            <a href="javascript:void(0);" class=" btnnext"></a>     
        </div>
        <!-- End result -->

        <!-- summary -->
        <div class="chall-subtitle"><span>•</span>导师代表总结</div>
        <dl class="chall-summary">
            <dt>
                <img width="192" height="244" alt="导师:姜传震" src="{{ assetUrlByCdn('/static/activity/challenge/images/tutor07.jpg') }}" />
                <p><strong>导师:姜传震</strong></p>
                <p>耀盛中国<br>普付宝执行副总裁</p>
            </dt>
            <dd>
                <p>第一组，给我的最深的感受是专业、细致！他们选择的课题是非常难的题，解题能力非常的好，恰到好处的总结了未来的一个愿景。第二组，像郭德纲讲相声，有创意有场景化，团队配合非常突出，都有参与进来。第三组，从宏观把控从运营管理方面去做了多方面的总结，特别突出，体现了一个人的格局，从未来发展当中具有宏观把控的发展能力，不仅工作，包括家庭生活。第四组，突出了知识面的广泛，对人才对市场的规划都有所涉及，从很小的话题，上升到很广的方面，跳出了话题本身的内容，很有意思。第五组，表达优秀，思维敏捷，个人突出，带动了整体团队作战效果。第六组，具有超强的实践能力，我相信在未来九斗鱼改版的过程中，一定有你们总结出的思维方式，而且最重要的阐述了方法论，这是一个高深莫测的问题，方法论是我们做任何事情都要事先探讨的，“我们在一个团队中，不可能是一个全能的小巨人，不可能什么都会，所以我们在这个团队中有胳膊有腿有鼻子有眼睛，每个人行使不同的职责，付出不同的贡献。”而这组同学就用了很好的方法论告诉了我们基于什么需求要做什么改变，并有一个很完美的回答。第七组，体现了一个对未知的领域的一个预判能力，团队分工明确，从规划到未来愿景的预判，逻辑很清晰。</p>
                <p>大家很优秀，我也很羡慕！第一，很优秀很超强的学习能力，对于没有涉及到领域能做的很好，我坚信以后大家在任何一个工作岗位上都可以去茁壮成长，有自己的一份成绩！第二，有气场。在我上大学的时候，每天能够西装革履是一件非常难得的事情，包括言谈举止，都能体现出大家的气场！每个人都有自己的特点，这种特点在你以后的工作中至关重要！第三，大家做PPT的能力，一流，虽然有可进步的空间，但是，作为现阶段来讲，已经可以看到大家的未来！PPT是总结的一种方式。第四，大家有特别好的思维方式，一个人的思维方式决定了他走多远走多高！事事都是相通，好的思维方式，一定会做好。</p>
                <p>最后，祝愿大家都取得好成绩，走的够远！</p>
            </dd>
        </dl>
        <!-- End summary -->
        <div class="clear"></div>
        <!-- result -->
        <div class="chall-subtitle"><span>•</span>赛果公布</div>
        <dl class="chall-result">
            <dt><img width="420" height="304" alt="" src="{{ assetUrlByCdn('/static/activity/challenge/images/img09.jpg') }}" /></dt>
            <dd>
                <ul>
                    <li><span>第一名  南开大学——蓝开Elites队</span><em>54.2分</em></li>
                    <li><span>第二名  中央财经大学——金牛队</span><em>53.4分</em></li>
                    <li><span>第三名  天津外国语大学——重案六组队</span><em>52.8分</em></li>
                    <li><span>第四名  对外经济贸易大学——cosmos队</span><em>52.7分</em></li>
                    <li><span>第五名  河北农业大学——三才九尾队</span><em>51.3分</em></li>
                    <li><span>第六名  昌航江财——Summttttzie Chou （粟子粥队）</span><em>49.8分</em></li>
                    <li><span>第七名  湖北经济学院法商学院——华尔街之狼队</span><em>48.5分</em></li>
                    <li><span>第八名  吉林大学——JLU&VC之鹰队</span><em>弃赛</em></li>
                </ul>
            </dd>
        </dl>
        <!-- End result -->

        <!-- group -->
        <dl class="chall-group">
            <dt><span>•</span>合影留念</dt>
            <dd><img width="689" height="394" alt="" src="{{ assetUrlByCdn('/static/activity/challenge/images/img10.jpg') }}" /></dd>
        </dl>
        <!-- End group -->

        <img src="{{ assetUrlByCdn('/static/activity/challenge/images/media.jpg') }}" />
    </div>
    




@endsection
@section('jspage')
<script type="text/javascript" src="{{ assetUrlByCdn('/static/activity/challenge/js/zzsc.js') }}"></script>
<script type="text/javascript">

//海报
jQuery("#chall-pic").jCarouselLite({
    auto:5000,
    speed:300,
    visible:1,
    vertical:false,
    stop:$("#chall-pic"),
    btnPrev:"#chall-pic-prev",
    btnNext:"#chall-pic-next"});
(function($){
    $(function(){
        $(".chall-process li").each(function(){
            $(this).hover(function(){
                $(this).find(".call-process-box").fadeIn();
            },function(){
                $(this).find(".call-process-box").fadeOut();

            })
        })
    })
})(jQuery)
</script>
@endsection



