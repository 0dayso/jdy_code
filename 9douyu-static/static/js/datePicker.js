(function($){
    $(document).ready(function() {
        var startTimeFormatArr = {
            simple: {},
            default: {maxDate:'#F{$dp.$D(\'end_time\')||\'%y-%M-%d\'}'}
        }
        var endTimeFormatArr = {
            simple: {minDate:'#F{$dp.$D(\'start_time\')}'},
        	//default: {}
            default: {minDate:'#F{$dp.$D(\'start_time\')}'}
        }
        var nomaxdate = $("#end_time").attr("nomaxdate");

        if(!nomaxdate) {
        	endTimeFormatArr.default.maxDate = '%y-%M-%d';
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