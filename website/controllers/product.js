(function(){
    var PH = angular.module('PH');
    PH.controller('productCtrl',productCtrl);
    productCtrl.$inject = ['$scope','$filter', 'DialogService'];
    function productCtrl($scope,$filter,DialogService) {
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
                        $(".items-cnt").html("");
                        for(var i=0;i<products.length;i++){
                            var price= $filter('currency')(products[i].price);
                            var showImg=JSON.parse(products[i].img)[0].path;
                            var hiddenImg=JSON.parse(products[i].img)[1].path;

                            var str ="<article class='items-box'>\n" +
                                "                        <div class='items-box-side'><a href='#' class='items-cart'></a> <a href='#'\n" +
                                "                                                                                           class='items-like'></a></div>\n" +
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
                        }
                    }
                },
                error:function(){
                    DialogService.OpenMessage(1,
                        "商品列表",
                        "服務繁忙，請稍後重試", null);
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
        confirmHref(type,typeValue);
    });

    $(".filter-submenu").click(function () {
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

        confirmHref(type,typeValue);//把旧参数替换新收集的参数
    });

    function confirmHref(type,typeValue){
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
