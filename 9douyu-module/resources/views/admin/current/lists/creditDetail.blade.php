@extends('admin/layouts/default')

@section('content')
    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="/admin">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="#">借款人列表</a></li>
    </ul>

    @if(Session::has('message'))
        <div class="alert alert-success alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4>  <i class="icon fa fa-check"></i> 提示！</h4>
            {{ Session::get('message') }}
        </div>
    @endif


    <div class="row-fluid sortable">
        <div class="box span12">
            <div class="box-header" data-original-title>
                <h2><i class="halflings-icon user"></i><span class="break"></span>借款人列表</h2>
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
                        <th>姓名</th>
                        <th>身份证号</th>
                        <th>借款金额</th>
                        <th>借款日期</th>
                        <th>所在地</th>

                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    if(isset($paginate['data']) && !empty($paginate['data'])){
                    foreach($paginate['data'] as $key => $item){
                    ?>
                    <tr>
                        <td class="center"><?php echo $item['name'];?> </td>
                        <td class="center"><?php echo $item['id_card'];?> </td>

                        <td class="center"><?php echo $item['amount'] ;?> 元 </td>
                        <td class="center"><?php echo $item['time'];?> </td>
                        <td class="center"><?php echo $item['address'];?> </td>


                    </tr>
                    <?php
                    }
                    }
                    ?>
                    </tbody>
                </table>
                @include('scripts/paginate', ['paginate'=>$paginate])

            </div>
        </div><!--/span-->

    </div><!--/row-->
@stop