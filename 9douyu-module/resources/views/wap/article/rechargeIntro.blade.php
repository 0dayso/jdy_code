@extends('wap.common.wapBase')

@section('title', '充值说明')

@section('keywords', "{{env('META_KEYWORD')}}")

@section('description', "{{env('META_DESCRIPTION')}}")
@section('css')
<style type="text/css">
    body{ background: #f2f2f2;}
    .re-intro{ background: #fff; padding: 0.8rem 0.6rem 0.9rem; font-size: 0.75rem; color: #58bffc; margin-bottom: 0.25rem;}
    .re-intro h4{ padding-bottom: 0.5rem;}
    .re-intro span{ display: inline-block; width: 0.95rem; height: 0.95rem; background: #54bff6; line-height: 0.98rem; font-size: 0.75rem; text-align: center;font-weight: bold; color: #fff; border-radius: 100%; font-family: "Arial"; margin-right: 0.7rem;vertical-align: top;  }
    .re-intro p{ font-size: 0.6rem; color: #999999; padding-left: 1.65rem; line-height: 0.85rem;}
</style>
@endsection

@section('content')
    <div class="re-intro">
        <h4><span>1</span>最低的充值金额是多少？</h4>
        <p>快捷充值最低100元。</p>
    </div>
    <div class="re-intro">
        <h4><span>2</span>充值有手续费吗？</h4>
        <p>充值不收取用户手续费。</p>
    </div>
    <div class="re-intro">
        <h4><span>3</span>充值什么时候可以到账？</h4>
        <p>充值一般实时到账。</p>
    </div>
    <div class="re-intro">
        <h4><span>4</span>可以用信用卡进行充值吗？</h4>
        <p>九斗鱼严禁信用卡充值、套现等行为，一经发现将予以处罚。</p>
    </div>
    <div class="re-intro">
        <h4><span>5</span>充值有限额吗？</h4>
        <p>每日的充值限额依据各银行限额为准，用户发起充值时，会提醒用户单笔限额和当日限额</p>
    </div>
@endsection