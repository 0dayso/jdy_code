var mySwiper = new Swiper ('.swiper-container', {
    direction: 'vertical',
    loop: true,

    // 如果需要分页器
    //pagination: '.swiper-pagination',
    onInit: function(swiper){ //Swiper2.x的初始化是onFirstInit
        swiperAnimateCache(swiper); //隐藏动画元素
        swiperAnimate(swiper); //初始化完成开始动画
    },
    onSlideChangeEnd: function(swiper){
        swiperAnimate(swiper); //每个slide切换结束时也运行当前slide动画
    }

})
//
gSound = '/static/weixin/newspaper1603.mp3';
document.onreadystatechange = loading;
function loading(){
    if(document.readyState == "complete")
    {
        playbksound();

    }
}

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
    bg_htm = "<img id='sound_image' src='/static/weixin/images/news1603/new03-m.png' class='img-icon-1 play'>";
    sound_div.innerHTML = bg_htm ;
    document.body.appendChild(sound_div);
}