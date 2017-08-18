(function(){

    var now = { row:1, col:1 }, last = { row:0, col:0};
    const towards = { up:1, right:2, down:3, left:4};
    var isAnimating = false;
    var pagetotal = $(".page").length;

    s=window.innerHeight/500;
    ss=250*(1-s);

// $('.wrap').css('-webkit-transform','scale('+s+','+s+') translate(0px,-'+ss+'px)');

    document.addEventListener('touchmove',function(event){
        event.preventDefault(); },false);

    $(document).swipeUp(function(){
        if (isAnimating) return;
        last.row = now.row;
        last.col = now.col;
        if (last.row != pagetotal) { now.row = last.row+1; now.col = 1; pageMove(towards.up);}
        if(last.row == pagetotal){ now.row = 1; now.col = 1; pageMove(towards.up);}

    })

    $(document).swipeDown(function(){
        if (isAnimating) return;
        last.row = now.row;
        last.col = now.col;
        if (last.row!=1) { now.row = last.row-1; now.col = 1; pageMove(towards.down);}
        if(last.row == 1){ now.row = pagetotal; now.col = 1; pageMove(towards.down);}
    })

    function pageMove(tw){
        var lastPage = ".page-"+last.row+"-"+last.col,
            nowPage = ".page-"+now.row+"-"+now.col;

        switch(tw) {
            case towards.up:
                outClass = 'pt-page-moveToTop';
                inClass = 'pt-page-moveFromBottom';
                break;
            case towards.right:
                outClass = 'pt-page-moveToRight';
                inClass = 'pt-page-moveFromLeft';
                break;
            case towards.down:
                outClass = 'pt-page-moveToBottom';
                inClass = 'pt-page-moveFromTop';
                break;
            case towards.left:
                outClass = 'pt-page-moveToLeft';
                inClass = 'pt-page-moveFromRight';
                break;
        }

        isAnimating = true;



        $(lastPage).addClass(outClass);
        $(nowPage).addClass(inClass);

        setTimeout(function(){
            $(lastPage).removeClass('page-current');
            $(lastPage).removeClass(outClass);
            $(lastPage).addClass("hide");
            $(lastPage).find("img").addClass("hide");

            $(nowPage).removeClass("hide");
            $(nowPage).addClass('page-current');
            $(nowPage).removeClass(inClass);
            $(nowPage).find("img").removeClass("hide");

            isAnimating = false;
        },600);
    }


})();

var pop_up_note_mode = true;
var note_id = 1;

function $$(name) {
    return document.getElementById(name);
}
function switchsound() {
    au = $$('bgsound');
    ai = $$('sound_image');
    if (au.paused) {
        au.play();
        pop_up_note_mode = true;
        ai.className = 'img-icon-1 play';
    }
    else {
        pop_up_note_mode = false;
        au.pause();
        ai.className = 'img-icon-1';

    }
}

function playbksound() {
    var audiocontainer = $$('audiocontainer');
    if (audiocontainer != undefined) {
        audiocontainer.innerHTML = '<audio id="bgsound" loop="loop" autoplay="autoplay"> <source src="' + gSound + '" /> </audio>';
    }

    var audio = $$('bgsound');
    audio.play();
    sound_div = document.createElement("div");
    sound_div.setAttribute("ID", "cardsound");
    sound_div.style.cssText = "position:absolute;right:0.75rem;top:0.5rem;z-index:50000;visibility:visible;";
    sound_div.onclick = switchsound;
    bg_htm = "<img id='sound_image' src='"+musicImg+"' class='img-icon-1 play'>";
    sound_div.innerHTML = bg_htm ;
    document.body.appendChild(sound_div);
}
//播放音频文件
// gSound = '/static/weixin/paper/voice/newspaper1611.mp3';
// document.onreadystatechange = loading;
// function loading(){
//     if(document.readyState == "complete")
//     {
//         playbksound();

//     }
// }