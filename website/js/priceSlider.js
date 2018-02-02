jQuery(document).ready(function ($) {
    //价钱效果
    // console.log($('.price-line').width());
    // console.log($('.begin-box').position().left);
    // 最小box的left
    // console.log("filter-price:width:  " + $('#filter-price').width());

    $('#price_title').click(function () {
        if ($('#price_submenu').css('display') == 'block'){
            //价钱效果
            // console.log($('.price-line').width());
            // console.log($('.begin-box').position().left);
            // 最小box的left
            var min_left = $('.begin-box').position().left;
            console.log('min_left====>'+min_left);
            // 最大box的left
            var max_left = $('.end-box').position().left;
            console.log('min_left' + min_left, 'max_left' + max_left);

            //拖动
            var flagBeginDrag = false;
            var flagEndDrag = false;
            var offsetL = $('.price-range').offset().left;
            // var offsetR = $('.price-range').offset().right;
            // console.log($('.price-range').offset().left);

            // begin
            $('.begin-box').mousedown(function () {
                flagBeginDrag = true;
            });
            $('.price-range').mousemove(function () {
                if (flagBeginDrag) {
                    //begin-box来到最左边
                    if (event.pageX < offsetL) {
                        $('.begin-box').css('left', 0);
                        $('.sel-line').css({
                            'left': 0,
                            'width': max_left
                        });
                        min_left = 0;
                        funcPercent();
                    }
                    //begin-box来到最右边
                    // else if (event.pageX > offsetL + $('.price-range').width()) {
                    //     $('.begin-box').css('left', $('.price-range').width());
                    //     $('.sel-line').css({
                    //         'width': $('.price-range').width() - max_left
                    //     });
                    //     min_left = max_left;
                    //     max_left = $('.price-range').width();
                    //     funcPercent();
                    // }
                    //begin-box不越过end-box
                    else if (event.pageX < offsetL + max_left) {
                        $('.begin-box').css('left', event.pageX - offsetL);
                        $('.sel-line').css({
                            'left': event.pageX - offsetL,
                            'width': max_left - (event.pageX - offsetL)
                        });
                        min_left = event.pageX - offsetL;
                        funcPercent();
                    }
                    //begin-box越过end-box
                    else {
                        // $('.begin-box').css('left', event.pageX - offsetL);
                        // $('.sel-line').css({
                        //     'width': event.pageX - offsetL - max_left
                        // });
                        // min_left = max_left;
                        // max_left = event.pageX - offsetL;
                        // alert('.begin-box越界');
                        return;
                    }
                }
            });

            // end
            $('.end-box').mousedown(function () {
                flagEndDrag = true;
            });
            $('.price-range').mousemove(function (event) {
                if (flagEndDrag) {
                    // end-box来到最左边
                    // if (event.pageX < offsetL) {
                    //     $('.end-box').css('left', 0);
                    //     $('.sel-line').css({
                    //         'left': 0,
                    //         'width': min_left
                    //     });
                    //     max_left = min_left;
                    //     min_left = 0;
                    //     funcPercent();
                    // }
                    // end-box来到最右边
                    if (event.pageX > offsetL + $('.price-range').width()) {
                        $('.end-box').css('left', $('.price-range').width());
                        $('.sel-line').css({
                            'width': $('.price-range').width() - min_left
                        });
                        max_left = $('.price-range').width();
                        funcPercent();
                    }
                    // end-box不越过begin-box
                    else if (event.pageX >= offsetL + min_left) {
                        $('.end-box').css('left', event.pageX - offsetL);
                        $('.sel-line').css({
                            'width': event.pageX - offsetL - min_left
                        });
                        max_left = event.pageX - offsetL;
                        funcPercent();
                    }
                    // end-box越过begin-box
                    else {
                        // $('.eng-box').css('left', event.pageX - offsetL);
                        // $('.sel-line').css({
                        //     'left': event.pageX - offsetL,
                        //     'width': min_left - (event.pageX - offsetL)
                        // });
                        // max_left = min_left;
                        // min_left = event.pageX - offsetL;
                        // alert('.end-box越界');
                        // funcPercent();
                    }
                }
            });

            document.addEventListener('mouseup', function () {
                flagBeginDrag = false;
                flagEndDrag = false;
            });

            // 取消不必要的点击事件
            $('.begin-box span,.end-box span,.begin-box,.end-box').click(function () {
                event.stopPropagation();
            });

            // 动态显示百分比
            funcPercent();
            function funcPercent() {
                var beginPer = min_left / $('.price-line').width();
                var endPer = max_left / $('.price-line').width();
                $('.begin-box span').html(Math.round(beginPer * 100)*500);
                $('.end-box span').html(Math.round(endPer * 100)*500 );

            }
        }
    })




})