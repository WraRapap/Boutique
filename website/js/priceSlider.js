// desktop-slider
jQuery(document).ready(function ($) {
    //价钱效果
    // console.log($('.price-line').width());
    // console.log($('.begin-box').position().left);
    // 最小box的left
    // console.log("filter-price:width:  " + $('#filter-price').width());

    $('.price_title').click(function (e) {
        // console.log('price_title被點擊了');
        // console.log($(this).find('div.price_submenu').css('display'));
        if ($(this).find('div.price_submenu').css('display') == 'block'){
            console.log('true');
            // showSlide();

            var beginBox = $(this).find('div.begin-box');
            var endBox = $(this).find('div.end-box');
            var priceRange= $(this).find('div.price-range')
            var selLine = $(this).find('div.sel-line');
            var priceLine = $(this).find('div.price-line');

            var min_left = beginBox.position().left;
            console.log('min_left====>'+min_left);
            // 最大box的left
            var max_left = endBox.position().left;
            console.log('min_left' + min_left, 'max_left' + max_left);

            //拖动
            var flagBeginDrag = false;
            var flagEndDrag = false;
            var offsetL = priceRange.offset().left;
            // var offsetL = priceLine.offset().left;
            // var offsetR = $('.price-range').offset().right;
            // console.log($('.price-range').offset().left);

            // begin
            beginBox.mousedown(function () {
                flagBeginDrag = true;
            });
            priceRange.mousemove(function () {
                if (flagBeginDrag) {
                    //begin-box来到最左边
                    if (event.pageX < offsetL) {
                        beginBox.css('left', 0);
                        selLine.css({
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
                        beginBox.css('left', event.pageX - offsetL);
                        selLine.css({
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
            endBox.mousedown(function () {
                flagEndDrag = true;
            });
            priceRange.mousemove(function (event) {
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
                    if (event.pageX > offsetL + priceLine.width()) {

                        endBox.css('left', priceLine.width());
                        selLine.css({
                            'width': priceLine.width() - min_left
                        });
                        max_left = priceLine.width();
                        funcPercent();
                    }
                    // end-box不越过begin-box
                    else if (event.pageX >= offsetL + min_left) {
                        endBox.css('left', event.pageX - offsetL);
                        selLine.css({
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

});

// mobile-slider
jQuery(document).ready(function ($) {
    var begin_box = $('.mobile .begin-box');
    var sel_line = $('.mobile .sel-line');
    var end_box = $('.mobile .end-box');
    var price_line = $('.mobile.price-line');

    $('.price_title').click(function (e) {
        console.log($('#price_mobile_submenu').css('display'));

        if ($('#price_mobile_submenu').css('display') == "block") {
            // .mobile.price-line的left
            var offsetL = price_line.offset().left;
            // 最小box的offsetLeft和left
            var min_offsetLeft = begin_box.offset().left;
            var min_left = begin_box.position().left;
            // 最大box的offsetLeft
            var max_offsetLeft = end_box.offset().left;
            var max_left = end_box.position().left;
            console.log('offsetL:', offsetL, 'min_offsetLeft:', min_offsetLeft, 'max_offsetLeft:', max_offsetLeft);

            //拖动
            //begin
            begin_box.on('touchmove', function (e) {
                var e_target = e.targetTouches[0];
                var e_pageX = e.targetTouches[0].pageX;
                // console.log(e_target);
                // console.log(e_pageX);

                //begin-box来到最左边
                if (e_pageX < offsetL) {
                    begin_box.css('left', 0);
                    sel_line.css({
                        'left': 0,
                        'width': max_left
                    });
                    min_left = 0;
                    funcPercent();
                }
                // begin-box不越过end-box
                else if (e_pageX < offsetL + max_left) {
                    begin_box.css('left', e_pageX - offsetL);
                    sel_line.css({
                        'left': e_pageX - offsetL,
                        'width': max_left - (e_pageX - offsetL)
                    });
                    min_left = e_pageX - offsetL;
                    funcPercent();
                }
            });

            // end
            end_box.on('touchmove', function (e) {
                var e_target = e.originalEvent.targetTouches[0];
                var e_pageX = e.originalEvent.targetTouches[0].pageX;
                // console.log(e_target);
                // console.log(e_pageX);

                //end-box来到最右边
                if (e_pageX > offsetL + price_line.width()) {
                    end_box.css('left', price_line.width());
                    sel_line.css({
                        'width': price_line.width() - min_left
                    });
                    max_left = price_line.width();
                    funcPercent();
                }
                // end-box不越过begin-box
                else if (e_pageX >= offsetL + min_left) {
                    end_box.css('left', e_pageX - offsetL);
                    sel_line.css({
                        'width': e_pageX - (offsetL + min_left)
                    });
                    max_left = e_pageX - offsetL;
                    funcPercent();
                }
            });

            // 动态显示百分比
            funcPercent();
            function funcPercent() {
                var beginPer = min_left / $('.price-line').width();
                var endPer = max_left / $('.price-line').width();
                $('.begin-box span').html(Math.round(beginPer * 100) * 500);
                $('.end-box span').html(Math.round(endPer * 100) * 500);
            }
        }
    });
});