function GetQueryString(name)
{
    var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    var r = window.location.search.substr(1).match(reg);
    if(r!=null)return  unescape(r[2]); return null;
}

function collectFly(addcar,event) {
    var offset = null;
    if ($(document).width()<1170){
        offset = $(".fa-heart").offset();
    }else if($(document).width()>=1170){
        offset = $("#end").offset();
    }
    console.log(offset);
    // console.log(addcar);
    var flyer = $('<img class="u-flyer" src="../website/img/like.png">').clone().css({
        'z-index': '999',
        'height': '3rem',
        'width': '3rem'
    });
    // console.log(flyer);
    flyer.fly({
        start: {
            left: event.pageX,
            top: event.pageY - $(document).scrollTop()
        },
        end: {
            left: offset.left + 10,
            top: offset.top - $(document).scrollTop() +12,
            width: 0,
            height: 0
        },
        onEnd: function(){
            // $("#msg").show().animate({width: '250px'}, 200).fadeOut(1000);
            // addcar.css("cursor","default").removeClass('orange').unbind('click');
            // this.destory();
        }
    });
};