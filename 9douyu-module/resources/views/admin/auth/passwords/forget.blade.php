@extends('layouts.admin-login')

<!-- Main Content -->
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Reset Password</div>
                    <div class="panel-body">


                        <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/password/sendToEmail') }}">
                            @if(Session::has('message'))
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{ Session::get('message') }}
                                </div>
                            @elseif($errors->first())
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    <strong>{{ $errors->first() }}!</strong>
                                </div>
                            @else
                            @endif
                            {!! csrf_field() !!}

                            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">E-Mail Address</label>

                                <div class="col-md-6">
                                    <input type="email" class="form-control" name="email" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('verify') ? ' has-error' : '' }}">
                                <label class="col-md-4 control-label">Verify Number</label>

                                <div class="col-md-6">
                                    <input type="text" class="form-control" name="verify" value="{{ old('verify') }}">

                                    @if ($errors->has('verify'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('verify') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-btn fa-envelope"></i>Send Reset Email
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
