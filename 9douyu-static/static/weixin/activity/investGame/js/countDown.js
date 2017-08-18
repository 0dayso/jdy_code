var countDown   = {
    getRTime:function(classNode,lastTime){
        var serverTime  =   $("."+classNode).attr('attr-spike-time');
        var endTime     =   new Date(lastTime);
        var nowTime     =   new Date(serverTime);
        var t =endTime.getTime() - nowTime.getTime();
        var d=0;
        var h=0;
        var m=0;
        var s=0;
        if(t>0){

            h=Math.floor(t/1000/60/60%24);
            if(h < 10){
                oh = '0';
                sh = h;
            }else{
                oh = Math.floor(h/10);
                sh = Math.floor(h%10);
            }
            m=Math.floor(t/1000/60%60);

            if(m < 10 ){
                om = '0';
                sm = m;
            }else{
                om= Math.floor(m/10);
                sm= Math.floor(m%10);
            }

            s=Math.floor(t/1000%60);
            if( s < 10 ){
                os = '0';
                ss = s;
            }else{
                os = Math.floor(s/10);
                ss = Math.floor(s%10);
            }

            document.getElementById("t_h1").innerHTML   = oh;
            document.getElementById("t_h").innerHTML    = sh;
            document.getElementById("t_m1").innerHTML   = om;
            document.getElementById("t_m").innerHTML    = sm;
            document.getElementById("t_s1").innerHTML   = os;
            document.getElementById("t_s").innerHTML    = ss;
        }else {

            window.location.reload();
        }
        getNextTime(classNode);
    }
};
function getNextTime(classNode){
        var serverTime  =   $("."+classNode).attr('attr-spike-time');
        var nowTime     =   new Date(serverTime);
        var t1 = nowTime.getTime();
        t1 +=1000;
        var nowTime1 = new Date(t1);
        var y1 = nowTime1.getFullYear();
        var m1 = nowTime1.getMonth()+1;
        var d1 = nowTime1.getDate();
        var h1 = nowTime1.getHours();
        var i1 = nowTime1.getMinutes();
        var s1 = nowTime1.getSeconds();
        $("."+classNode).attr('attr-spike-time',y1+"/"+m1+"/"+d1+" "+h1+":"+i1+":"+s1);
    }
