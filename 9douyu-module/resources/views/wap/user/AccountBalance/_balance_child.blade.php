@if(!empty($LogList['data']))
@foreach ( $LogList['data'] as $list )
    <div class="w-b-list bb-1px w-ye-pr15px">
        <p class="font14px"><span class="fl">{{ $list["note"] }}</span><span class="fr w-red-color">{{ $list["balance_change"] }}</span></p>
        <div class="clear"></div>
        <p class="w-8c-color"><span class="fl">{{ $list["created_at"] }}</span><span class="fr"> {{ $list['note_other'] or null }}</span></p>
        <div class="clear"></div>
    </div>
@endforeach
@endif