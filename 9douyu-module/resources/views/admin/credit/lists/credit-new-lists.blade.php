@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">债权列表</a></li>
    </ul>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif

    <form name="form1" action="" method="get">
        <div class="control-group">
            企业名称:
                <input type="text" class=" typeahead" name="company_name" value="{{ isset($pageParam['company_name']) ? $pageParam['company_name'] : null }}" />
            债权人名:
                <input type="text" class=" typeahead" name="loan_username" value="{{ isset($pageParam['loan_username']) ? $pageParam['loan_username'] : null }}" />
            </br>
            债权来源:
                <select id="selectSource" name="source" data-rel="chosen">
                    <option value="">全部</option>
                    @if(isset($source)))
                    @foreach($source as $key => $title)
                        <option value="{{$key}}" @if(($key == (isset($pageParam['source']) ? $pageParam['source'] : null)))selected @endif >{{ $title }}</option>
                    @endforeach
                    @endif
                </select>

            产品线:
                <select id="selectTag" name="credit_tag" data-rel="chosen">
                    <option value="">全部</option>
                    @if(isset($source)))
                    @foreach($productLine as $key => $title)
                        <option value="{{$key}}" @if(($key == (isset($pageParam['credit_tag']) ? $pageParam['credit_tag'] : null)))selected @endif >{{ $title }}</option>
                    @endforeach
                    @endif
                </select>
                <input style="margin-left: 30px;margin-bottom: 5px;" type="submit" class="btn btn-small btn-primary" value="点击搜索">

        </div>
    </form>

    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>债权列表</h2>
                <div class="box-icon">
                    <a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>
                    <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                    <a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-striped table-bordered bootstrap-datatable">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>标签</th>
                        <th>【企业/计划】名称</th>
                        <th>借款金额</th>
                        <th>利率</th>
                        <th>还款方式</th>
                        <th>到期日期</th>
                        <th>借款期限</th>
                        <th>合同编号</th>
                        <th>使用记录</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($list) && !empty($list))
                    @foreach($list as $key => $item)
                    <tr>
                        <td class="center">{{ $item->id }}</td>
                        <td class="center"> @if( isset( $productLine[$item->credit_tag] ) ) {{ $productLine[$item->credit_tag] }} @else 未定义标签 @endif</td>
                        <td class="center">{{ $item->company_name }} </td>
                        <td class="center">{{ $item->loan_amounts }} 万元 </td>
                        <td class="center">{{ $item->interest_rate }}</td>
                        <td class="center">{{ isset($repaymentMethod[$item->repayment_method]) ? $repaymentMethod[$item->repayment_method] : $item->repayment_method }}</td>
                        <td class="center">{{ $item->expiration_date }}</td>
                        <td class="center">{{ $item->loan_deadline . ' ['.$dayOrMonth[$item->repayment_method] .']' }} </td>
                        <td class="center">{{ $item->contract_no }} </td>
                        <td class="center">
                            @if(!empty($item->projectLinks_array))
                                @foreach($item->projectLinks_array as $credit_key => $projectLink)
                                    {{ ' 项目Id：'.$projectLink['project_id'] }}
                                @endforeach
                            @endif
                        </td>
                        <td class="center">
                            <a class="btn btn-info" href="/admin/credit/edit/all/{{ $item->id }}">
                                <i class="halflings-icon white edit"></i>编辑债权
                            </a>
                            <p></p>
                            <a class="btn btn-info" href="/admin/credit/edit/extend/{{ $item->type == 50 ? $item->source : $item->type }}/{{ $item->id }} ">
                                <i class="halflings-icon white edit"></i>编辑详情
                            </a>
                        </td>
                    </tr>
                    @endforeach
                    @endif
                    </tbody>
                </table>

                {!! $list->appends($pageParam)->render() !!}

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop
