(function(){
    var PH = angular.module('PH');
    PH.filter('parseint',function(){
        return function(input){        //input是我们传入的字符串
            if (input) {
                return parseInt(input);
            }
        }
    });

    PH.controller('productCtrl',productCtrl);
    productCtrl.$inject = ['$scope','$filter'];
    function productCtrl($scope,$filter) {
        $scope.next=true;
        $scope.show=true;
        if(window.matchMedia('screen and (max-width: 1170px)').matches){//移动端使用
            $scope.show=false;

            $(window).scroll(function(){
                $(".js").remove();
                $(".guang").remove();
                if(!$scope.next){
                    $(".items-bottom").after("<div class='js' style=\"text-align: center;\">努力捞宝中....</div>");
                    return;
                }
                if (($(window).height() + $(window).scrollTop() + 60) >= $(document).height()) {
                    var totalPage= $filter('parseint')($scope.paginationConf.totalItems / $scope.paginationConf.itemsPerPage +1);
                    if($scope.paginationConf.currentPage<totalPage){
                        $scope.paginationConf.nextPage();
                    }else{
                        $(".items-bottom").after("<div class='guang' style=\"text-align: center;\">宝贝被捞光了....</div>");
                    }

                }
            });
        }

        $scope.sort=GetQueryString("so")==null?"":GetQueryString("so");
        $scope.clearGo=function () {
            var index =location.href.indexOf("&");
            if(index>-1){
                location.href= location.href.substr(0,index);
            }else{
                location.href=location.href;
            }
        };

        $scope.pageChange = function ()
        {
            $scope.next=false;
            var search="";
            if(location.search.indexOf("?")>-1){
                search=location.search+"&page=" + $scope.paginationConf.currentPage+"&count="+$scope.paginationConf.itemsPerPage;
            }else{
                search="?page=" + $scope.paginationConf.currentPage+"&count="+$scope.paginationConf.itemsPerPage;
            }
            $.ajax({
                type:"post",
                url:"product.html"+search,
                data:{"rq":"a"},
                dataType:"json",
                success:function(data){
                    var products=data.data;
                    if(products.length>0){
                        if(window.matchMedia('screen and (min-width: 1170px)').matches){
                            $(".items-cnt").html("");
                        }
                        for(var i=0;i<products.length;i++){
                            var price= $filter('currency')(products[i].price);
                            var showImg=JSON.parse(products[i].img)[0].path;
                            var hiddenImg=JSON.parse(products[i].img)[1].path;

                            var str ="<article class='items-box'>\n" +
                                "                        <div class='items-box-side'><a href='#' class='items-cart'></a> <a href='#'\n" +
                                "                                                                                           class='items-like' i='"+products[i].id+"'></a></div>\n" +
                                "                        <a href='item.html?i="+products[i].id+"'>\n" +
                                "                            <div class='items-box-main'>\n" +
                                "                                <div class='items-img reveal'><img\n" +
                                "                                        src='../system/files/"+showImg+"'> <img\n" +
                                "                                        class='hidden' src='../system/files/"+hiddenImg+"'></div>\n" +
                                "                                <div>\n" +
                                "                                    <h6><span>"+products[i].name+"</span></h6>\n" +
                                "                                    <h5><span>TWD "+ price +"</span></h5>\n" +
                                "                                </div>\n" +
                                "                            </div>\n" +
                                "                        </a></article>";

                            $(".items-cnt").append(str);
                            $scope.next=true;
                        }


                    }
                },
                error:function(){
                    showPrompt('商品列表', "服務繁忙，請稍後重試");


                }
            })
        };



        $scope.paginationConf =
        {
            currentPage: $("#currentPage").val(),
            totalItems: $("#totalItems").val(),
            itemsPerPage: $("#perPageItems").val(),
            pagesLength: 9,
            perPageOptions: [10, 20, 30, 40, 50],
            onChange: $scope.pageChange
        };


    };



    $("select[name=sort]").change(function(){
        var type=$(this).attr("remark");
        var typeValue=$(this).val();
        confirmHref(type,typeValue,1);
    });

    $("div.price-range").mouseup(function(){
        var minPrice = $(".begin-box").find("span").html();
        var maxPrice =$(".end-box").find("span").html();
        confirmHref("p1",minPrice+"&p2="+maxPrice,2);
    });

    $(".filter-submenu[id!=price_submenu]").click(function () {
        var type=$(this).attr("remark");
        var typeValue="";
        $(this).find(":checkbox").each(function () {
            if($(this).prop("checked")){
                typeValue+=$(this).val()+",";
            }
        });
        if(typeValue!=""){
            typeValue=typeValue.substr(0,typeValue.length-1);
        }
        console.log(typeValue);

        confirmHref(type,typeValue,1);//把旧参数替换新收集的参数
    });

    $(".items-cnt").on("click",".items-like",function(event){
        $.ajax({
            url:"/index.php/api/addLike",
            type:"post",
            data:{"i":$(this).attr("i")},
            dataType:"json",
            success:function(data){
                if(data.status==1){
                    // showPrompt('商品', "商品收藏成功");
                    collectFly($(this),event);
                }
                else if(data.status==-1){
                    showPrompt('商品', "請先登錄");
                }
                else{
                    showPrompt('商品', data.msg);
                }
            },
            error:function(){
                showPrompt('商品', "服務繁忙，請稍後重試");
            }
        })
    });

    function collectFly(addcar,event) {
        var offset = null;
        if ($(document).width() < 1170){
            offset = $(".fa-heart").offset();
        }else if($(document).width() >= 1170){
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
                $("#msg").show().animate({width: '250px'}, 200).fadeOut(1000);
                addcar.css("cursor","default").removeClass('orange').unbind('click');
                this.destory();
            }
        });
    };

    function confirmHref(type,typeValue,num){
        var url=location.href;
        console.log(url);
        var index =url.indexOf("&"+type+"=");
        if(index<0){
            index =url.indexOf("?"+type+"=");
        }
        if(index>-1){//条件参数存在

            var start = url.substr(0,index+1);
            console.log(start);
            var end = url.substr(index+1);
            console.log(end);
            var mindex=end.indexOf("&");
            console.log(mindex);
            if(mindex>-1){//条件参数后面还有参数，取得后面参数
                end=end.substr(mindex);
            }else{
                end="";
            }
            if(num==2){
                url = start+end;
                index =url.indexOf("&p2=");
                if(index<0){
                    index =url.indexOf("?p2=");
                }
                start = url.substr(0,index);
                end = url.substr(index+1);
                var mindex=end.indexOf("&");
                if(mindex>-1){//条件参数后面还有参数，取得后面参数
                    end=end.substr(mindex);
                }else{
                    end="";
                }
            }
            console.log(end);
            var afterReplaceParm="";
            if(typeValue!=""){
                afterReplaceParm=type+"="+typeValue;
            }else{
                start=start.substr(0,start.length-1)
            }

            var search= start+afterReplaceParm+end;//最后确定查询参数
            console.log(search);
            window.location.href=search;
        }else{//条件参数之前不存在
            if(location.href.indexOf("?")>-1){//已经有参数了
                location.href=location.href+"&"+type+"="+typeValue;
            }else{
                location.href=location.href+"?"+type+"="+typeValue;
            }
        }
    }


})()
