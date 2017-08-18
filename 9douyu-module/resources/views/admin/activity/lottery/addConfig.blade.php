@extends('admin/layouts/default')

@section('content')
    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="/admin/lottery/configList">奖品配置列表</a></li>
    </ul>
    @if(Session::has('fail'))
        <div class="alert alert-sucess alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
            {{ Session::get('fail') }}
        </div>
    @endif
    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon icon fa icon-ok"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="alert alert-danger" style="display:none;">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            <li></li>
        </ul>
    </div>
    <form id="addLotteryConfig"  action="/admin/lottery/doAddConfig" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>添加{{$typeList[$type]}}</h2>
                </div>
                <div class="box-header">
                    @foreach($typeList as $key => $item)
                        <a href="/admin/lottery/addConfig?type={{$key}}" class="btn @if($type == $key) btn-primary @endif">添加{{$item}}</a>
                    @endforeach
                </div>
                <div class="box-content form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="selectError">奖品类型</label>
                        <input class="input-xlarge focused"  type="hidden" name="type" value="{{$type}}" >
                        <div class="controls">
                            <span>{{$typeList[$type]}}</span>
                        </div>

                    </div>
                    @if( $type ==\App\Http\Dbs\Activity\LotteryConfigDb::LOTTERY_TYPE_ENTITY)
                        <div class="control-group">
                            <label class="control-label" for="selectError">奖品名词</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="name" type="text" name="name" value="" >
                                <span style="color:red;margin-left: 30px;">填写展示的奖品名词</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectError">奖品数量</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="number" type="text" name="number" value="0" >
                                <span style="color:red;margin-left: 30px;">奖品的数量</span>
                            </div>
                        </div>
                    @else

                        <div class="control-group">
                            <label class="control-label" for="selectError">{{$typeList[$type]}}名称</label>
                            <div class="controls">
                                @if( !empty($bonusList) )
                                    <select name="ticket_id" id="ticketId">
                                        @foreach($bonusList as $key => $item)
                                            <option value="{{$item['id']}}">{{$item['name']}}-{{$item['using_desc']}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input class="input-xlarge focused" id="ticketId" type="text" name="ticket_id" value="" >
                                @endif
                                <span style="color:red;margin-left: 30px;">请选择或者填写ID</span>
                            </div>

                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectError">奖品名称</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="name" type="text" name="name" value="" >
                                <span style="color:red;margin-left: 30px;">填写展示的奖品名称</span>
                            </div>
                        </div>

                    @endif
                    <div class="control-group">
                        <label class="control-label" for="selectError">中奖概率</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="rate" type="text" name="rate" value="" >
                            <span style="color:red;margin-left: 30px;">值越大,中奖的几率越高</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">展示位置</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="order_num" type="text" name="order_num" value="" >
                            <span style="color:red;margin-left: 30px;">填写展示位置:如1</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">奖品分组/等级</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="group" type="text" name="group" value="" >
                            <span style="color:red;margin-left: 30px;">填写展示奖品分组/等级</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 状态</label>
                        <div class="controls">
                            <select style="display: block;"   name="status">
                                <option value="10" >开启</option>
                                <option value="20" >关闭</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">  </label>
                        <div class="controls">
                            <button type="submit" class="btn btn-small btn-primary">添加奖品</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <pre>
        添加奖品说明:
        1,选择红包,加息券的类型; 2奖品名词是用在抽奖展示的; 3,中奖概率是,概率越大中奖几率越高 4, 奖品分组/等级只是用来控制某次抽奖的奖品类型

        限额说明:中奖概率只是在某一类抽奖活动需要对奖品的概率做调整进行限制的,跟真实的抽奖活动场景来区分的
    </pre>
@endsection
@section('jsScript')
    <script type="text/javascript">
        $(document).ready(function(){
            $(".btn-primary").on('click',function(){
                var name = $("#name").val();
                var rate = $("#rate").val();
                var order_num = $("#order_num").val();
                var group = $("#group").val();
                if(name == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写奖品名词');
                    return false;
                }
                if(rate == ''||rate =='0'){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写中奖概率');
                    return false;
                }
                if(order_num == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写位置');
                    return false;
                }
                if(group == ''||group =='0'){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请设置奖品的分组');
                    return false;
                }
                $("#addLotteryConfig").submit();
            })
        });
    </script>
@endsection