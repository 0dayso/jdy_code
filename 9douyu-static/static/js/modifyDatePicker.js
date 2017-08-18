(function($){
    $(document).ready(function() {
        var startTimeFormatArr = {
            simple: {},
            default: {}
        }
        var endTimeFormatArr = {
            simple: {minDate:'#F{$dp.$D(\'start_time\')}'},
            default: {minDate:'#F{$dp.$D(\'start_time\')}'}
        }
        if(typeof formatType == 'undefined') formatType = 'default';
        
        $("#start_time").click(function(){
            WdatePicker(startTimeFormatArr[formatType]);
        });
        
        $("#end_time").click(function(){
            WdatePicker(endTimeFormatArr[formatType]);
        });
    });
})(jQuery);