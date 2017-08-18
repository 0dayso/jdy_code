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
    <form id="addRate"  action="/admin/lottery/doEditConfig" method="post">

        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>编辑{{$typeList[$type]}}</h2>
                </div>
                <div class="box-header">
                    @foreach($typeList as $key => $item)
                        <a href="/admin/lottery/editConfig?type={{$key}}&id={{$lottery['id']}}" class="btn @if($type == $key) btn-primary @endif">编辑{{$item}}</a>
                    @endforeach
                </div>
                <div class="box-content form-horizontal">
                    <div class="control-group">
                        <label class="control-label" for="selectError">奖品类型</label>
                        <input class="input-xlarge focused"  type="hidden" name="type" value="{{$type}}" >
                        <input class="input-xlarge focused"  type="hidden" name="id" value="{{$id}}" >
                        <div class="controls">
                            <span>{{$typeList[$type]}}</span>
                        </div>

                    </div>
                    @if( $type ==\App\Http\Dbs\Activity\LotteryConfigDb::LOTTERY_TYPE_ENTITY)
                        <div class="control-group">
                            <label class="control-label" for="selectError">奖品名词</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="name" type="text" name="name" value="{{$lottery['name']}}" >
                                <span style="color:red;margin-left: 30px;">填写展示的奖品名词</span>
                            </div>
                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectError">奖品数量</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="number" type="text" name="number" value="{{$lottery['number']}}" >
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
                                            <option value="{{$item['id']}}" @if($lottery['foreign_id'] ==$item['id']) selected="selected" @endif>{{$item['name']}}-{{$item['using_desc']}}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <input class="input-xlarge focused" id="ticketId" type="text" name="ticket_id" value="{{$lottery['foreign_id']}}" >
                                @endif
                                <span style="color:red;margin-left: 30px;">请选择或者填写ID</span>
                            </div>

                        </div>
                        <div class="control-group">
                            <label class="control-label" for="selectError">奖品名词</label>
                            <div class="controls">
                                <input class="input-xlarge focused" id="name" type="text" name="name" value="{{$lottery['name']}}" >
                                <span style="color:red;margin-left: 30px;">填写展示的奖品名词</span>
                            </div>
                        </div>

                    @endif
                    <div class="control-group">
                        <label class="control-label" for="selectError">中奖概率</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="rate" type="text" name="rate" value="{{$lottery['rate']}}" >
                            <span style="color:red;margin-left: 30px;">值越大,中奖的几率越高</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">展示位置</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="order_num" type="text" name="order_num" value="{{$lottery['order_num']}}" >
                            <span style="color:red;margin-left: 30px;">填写展示的位置</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">奖品分组/等级</label>
                        <div class="controls">
                            <input class="input-xlarge focused" id="group" type="text" name="group" value="{{$lottery['group']}}" >
                            <span style="color:red;margin-left: 30px;">填写展示的奖品分组/等级</span>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError"> 状态</label>
                        <div class="controls">
                            <select style="display: block;"   name="status">
                                <option value="10" @if($lottery['status'] ==\App\Http\Dbs\Activity\LotteryConfigDb::LOTTERY_STATUS_SURE) selected="true" @endif>开启</option>
                                <option value="20" @if($lottery['status'] ==\App\Http\Dbs\Activity\LotteryConfigDb::LOTTERY_STATUS_FAILED) selected="true" @endif>关闭</option>
                            </select>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" for="selectError">  </label>
                        <div class="controls">
                            <button type="submit" class="btn btn-small btn-primary">编辑奖品</button>
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
                var phone = $("#phone").val();
                var cash = $("#cash").val();
                if(phone == ''){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('手机号码不能为空');
                    return false;
                }
                if(cash == ''||cash =='0'){
                    $('.alert-danger').slideDown();
                    $('.alert-danger ul li').html('请填写大于0的额度');
                    return false;
                }
                $("#addRate").submit();
            })
        });
    </script>
@endsection