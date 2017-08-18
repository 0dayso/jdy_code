@extends('admin/layouts/default')

@section('content')

    <script src="{{ assetUrlByCdn('theme/metro/My97DatePicker/WdatePicker.js') }} "></script>
    <style type="text/css">
        textarea{
            width: 800px;
        }
    </style>

    <ul class="breadcrumb">
        <li>
            <i class="icon-home"></i>
            <a href="index.html">控制台</a>
            <i class="icon-angle-right"></i>
        </li>
        <li><a href="javascript:void(0)">创建红包/加息券</a></li>
    </ul>
    <!-- start: Content -->
    <form role="form" action="/admin/bonus/doUpdate" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="id" value="<?php echo $obj->id ?>" />
        <div>
            @if(Session::has('fail'))
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4>  <i class="icon icon fa fa-warning"></i> 提示！</h4>
                    {{ Session::get('fail') }}
                </div>
            @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>


        <div class="row-fluid sortable">
            <div class="box span12">
                <div class="box-header" data-original-title>
                    <h2><i class="halflings-icon edit"></i><span class="break"></span>红包或加息券描述</h2>
                    <div class="box-icon">
                        {{--<a href="#" class="btn-setting"><i class="halflings-icon wrench"></i></a>--}}
                        <a href="#" class="btn-minimize"><i class="halflings-icon chevron-up"></i></a>
                        {{--<a href="#" class="btn-close"><i class="halflings-icon remove"></i></a>--}}
                    </div>
                </div>

                <div class="box-content form-horizontal">
                    <fieldset>
                            <div class="control-group">
                                <label for="type" class="control-label">类型：</label>
                                <div class="col-lg-6">

                                    <select id="type" name="type" data-rel="chosen">
                                        <?php
                                        foreach($type as $key => $title){
                                            echo "<option value=\"$key\" ". (($key == Input::old('type', $obj->type)) ? 'selected = "selected"' : null) ." >$title</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <div class="control-group">
                                <label for="name" class="control-label">&nbsp;名称：</label>
                                <div class="col-lg-6"><input type="text" name="name" class="form-control" value="{{ Input::old('name', $obj->name) }}"></div>
                            </div>

                            <div class="control-group">
                                <label for="use_type" class="control-label">使用类型：</label>
                                <div class="col-lg-6">

                                    <select id="use_type" name="use_type" data-rel="chosen">
                                        <?php
                                        foreach($useType as $key => $title){
                                            echo "<option value=\"$key\" ". (($key == Input::old('use_type', $obj->user_type)) ? 'selected = "selected"' : null) ." >$title</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                            <?php
                        $project_type_value = $client_type_value = [];
                                $project_type = Input::old('project_type',$obj->project_type);
                                if(is_string($project_type) && !empty($project_type)){
                                    $project_type = json_decode($project_type, true);
                                }
                                if(is_array($project_type)){
                                    $project_type_value = array_values($project_type);
                                }

                                $client_type  = Input::old('client_type', $obj->client_type);
                                if(is_string($client_type) && !empty($client_type)){
                                    $client_type = json_decode($client_type, true);
                                }

                                if(is_array($client_type)){
                                    $client_type_value = array_values($client_type);
                                }


                        $type = Input::old('type', $obj->type);
                        if($type == \App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_CURRENT){
                            $displayProject = 'display: none;';
                        }else{
                            $displayProject = '';
                        }

                            ?>
                            <div class="control-group" id="project_type_jdy" style="<?php echo $displayProject;?>">
                                <label for="project_type" class="control-label">&nbsp;可用项目类型：</label>
                                <div class="col-lg-6">
                                    <div>
                                        <?php
                                        foreach($productLine as $key => $title){
                                            $checked = in_array($key, $project_type_value) ? 'checked=""' : null;

                                            echo '<div style="display: block;float: left;width: 150px;">';
                                            echo "<label for='project-type-all-$key'>". $title ."</label>";
                                            echo "<input id='project-type-all-$key' class='project-type-item' type='checkbox'  value='". $key ."' name='project_type[]' " . $checked . "/>";
                                            echo '</div>';
                                        }
                                        ?>

                                    </div>

                                </div>
                            </div>

                            <div class="control-group">
                                <label for="client_type" class="control-label">&nbsp;投资端类型：</label>
                                <div class="col-lg-6">
                                    <?php
                                    foreach($client as $key => $title){
                                        $checked = in_array($key, $client_type_value) ? 'checked=""' : null;

                                        echo '<div style="display: block;float: left;width: 150px;">';
                                        echo "<label for='client_type-$key'>". $title ."</label>";
                                        echo "<input id='client_type-$key' class='project-type-item' type='checkbox'  value='". $key ."' name='client_type[]' ". $checked ."/>";
                                        echo '</div>';
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label for="using_range" class="control-label">&nbsp;使用范围：</label>
                                <div class="col-lg-6">
                                    <input type="text" name="using_desc" class="form-control" value="{{ Input::old('using_desc',$obj->using_desc) }}">
                                </div>
                            </div>

                        <?php
                        $type = Input::old('type', $obj->type);
                        if($type == \App\Http\Dbs\Bonus\BonusDb::TYPE_CASH){
                            $displayRate = 'display: none;';
                            $displayCash = '';
                        }else{
                            $displayRate = '';
                            $displayCash = 'display: none;';
                        }
                        ?>
                            <div style="display: block;" id="jdy-rate" data-bonus-type="interest" class="control-group bonus-type-input"style="<?php echo $displayRate;?>">
                                <label for="rate" class="control-label">&nbsp;利率：</label>
                                <div class="col-lg-6"><input type="text" name="rate" class="form-control" value="{{ Input::old('rate', $obj->rate) }}"><span class="add-on"> % </span></div>
                            </div>


                            <div data-bonus-type="cash" id="jdy-money" class="control-group bonus-type-input" style="<?php echo $displayCash;?>">
                                <label for="money" class="control-label">&nbsp;金额：</label>
                                <div class="col-lg-6"><input type="text" name="money" class="form-control" value="{{ Input::old('money', $obj->money) }}"><span class="add-on"> 元 </span></div>
                            </div>

                            <div class="control-group">
                                <label for="min_amount" class="control-label">&nbsp;最低金额：</label>
                                <div class="col-lg-6"><input type="text" name="min_money" class="form-control" value="{{ Input::old('min_money', $obj->min_money) }}"><span class="add-on"> 元 </span></div>
                            </div>

                            <div class="control-group">
                                <label for="send_start_date" class="control-label">&nbsp;发放开始时间：</label>
                                <div class="col-lg-6"><input type="text" name="send_start_date" id="send_start_date" onclick="WdatePicker({maxDate:'#F{$dp.$D(\'send_end_date\')||\'%y-%M-%d\'}'})" class="form-control" value="{{ Input::old('send_start_date', $obj->send_start_date) }}"></div>
                            </div>

                            <div class="control-group">
                                <label for="send_end_date" class="control-label">&nbsp;发放结束时间：</label>
                                <div class="col-lg-6"><input type="text" name="send_end_date" id="send_end_date" onclick="WdatePicker({minDate:'#F{$dp.$D(\'send_start_date\')}'})" class="form-control" value="{{ Input::old('send_end_date', $obj->send_end_date) }}"></div>
                            </div>

                            <div class="control-group">
                                <label for="effect_type" class="control-label">生效类型：</label>
                                <div class="col-lg-6">

                                    <select id="effect_type" name="effect_type" data-rel="chosen">
                                        <?php
                                        foreach($effectType as $key => $title){
                                            echo "<option value=\"$key\" ". (($key == Input::old('effect_type', $obj->effect_type)) ? 'selected = "selected"' : null) ." >$title</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>

                        <?php
                        $type = Input::old('effect_type', $obj->effect_type);
                        if($type == \App\Http\Dbs\Bonus\BonusDb::EFFECT_NOW){
                            $displayNow  = '';
                            $displayTime = 'display:none';
                        }else{
                            $displayNow  = 'display:none';
                            $displayTime = '';
                        }
                        ?>
                            <div class="control-group effect_day" style="<?php echo $displayNow;?>">
                                <label for="min_amount" class="control-label">&nbsp;生效期限（天）：</label>
                                <div class="col-lg-6"><input type="text" onkeyup="value=value.replace(/[^0-9]/g,'')" name="expires" id="expires" class="form-control" value="{{ Input::old('expires', $obj->expires) }}"></div>
                            </div>

                            <div class="control-group effect_time" style="<?php echo $displayTime;?>">
                                <label for="effect_start_date" class="control-label">&nbsp;生效开始时间：</label>
                                <div class="col-lg-6"><input type="text" name="effect_start_date" id="effect_start_date" onclick="WdatePicker({maxDate:'#F{$dp.$D(\'effect_end_date\')}'})" class="form-control" value="{{ Input::old('effect_start_date', $obj->effect_start_date) }}"></div>
                            </div>

                            <div class="control-group effect_time" style="<?php echo $displayTime;?>">
                                <label for="effect_end_date" class="control-label">&nbsp;生效结束时间：</label>
                                <div class="col-lg-6"><input type="text" name="effect_end_date" id="effect_end_date" onclick="WdatePicker({minDate:'#F{$dp.$D(\'effect_start_date\')}'})" class="form-control" value="{{ Input::old('effect_end_date', $obj->effect_end_date) }}"></div>
                            </div>

                            <?php
                        $type = Input::old('type');
                        if($type == \App\Http\Dbs\Bonus\BonusDb::TYPE_COUPON_CURRENT){
                            $display = '';
                        }else{
                            $display = 'display: none;';
                        }
                            ?>
                            <div class="control-group" id="current_day" style="<?php echo $display;?>">
                                <label for="current_day" class="control-label">&nbsp;零钱计划计息天数（天）：<font color="red">*</font></label>
                                <div class="col-lg-6"><input type="text" onkeyup="value=value.replace(/[^0-9]/g,'')" name="current_day" id="current_day" class="form-control" value="{{ Input::old('current_day', $obj->current_day) }}"></div>
                            </div>

                            <div class="control-group">
                                <label class="control-label">备注：<font color="red">*</font></label>
                                <div class="col-lg-6">
                                    <textarea cols="98" id="note" name="note">{{ Input::old('note', $obj->note) }}</textarea>
                                </div>
                            </div>

                        <div class="control-group">
                            <label for="type" class="control-label">状态：</label>
                            <div class="col-lg-6">
                                <select id="status" name="status" data-rel="chosen">
                                    <?php
                                    foreach($status as $key => $title){
                                        echo "<option value=\"$key\" ". (($key == Input::old('status', $obj->status)) ? 'selected = "selected"' : null) ." >$title</option>";
                                    }
                                    ?>
                                </select>

                            </div>
                        </div>

                            <div class="control-group">
                                <label for="type" class="control-label">是否可以转增：</label>
                                <div class="col-lg-6">
                                    <select id="assignment" name="give_type" data-rel="chosen">
                                        <?php
                                        foreach($assignment as $key => $title){
                                            echo "<option value=\"$key\" ". (($key == Input::old('give_type', $obj->give_type)) ? 'selected = "selected"' : null) ." >$title</option>";
                                        }
                                        ?>
                                    </select>

                                </div>
                            </div>
                    </fieldset>

                </div>
            </div>

        </div><!--/row-->

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">保存</button>
            <button type="reset" class="btn">重置</button>
        </div>

    </form>

@stop