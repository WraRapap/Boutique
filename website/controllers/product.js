(function(){
    var PH = angular.module('PH');
    PH.controller('productCtrl',productCtrl);
    productCtrl.$inject = ['$scope', 'DialogService'];
    function productCtrl($scope,DialogService) {
        $scope.paginationConf =
        {
            currentPage: 1,
            totalItems: $("#totalItems").val(),
            itemsPerPage: 2,
            pagesLength: 9,
            perPageOptions: [10, 20, 30, 40, 50],
            onChange: $scope.pageChange,
        };

        $scope.pageChange = function ()
        {
            //讀取那一頁的資料，替換資料
            self.currentPage = $scope.paginationConf.currentPage;
            var startIndex = (self.currentPage - 1) * self.itemsPerPage;

            $.ajax({
                type:"post",
                url:"product.html?",
                data:{"rq":"a"},
                dataType:"json",
                success:function(data){
                    var products=data.data;
                    if(products.length>0){
                        for(var i=0;i<products.length;i++){
                            var price= $filter('currency')(products[i].price);
                            var showImg=JSON.parse(products[i].img)[0].path;
                            var hiddenImg=JSON.parse(products[i].img)[1].path;

                            var str ="<article class='items-box'>\n" +
                                "                        <div class='items-box-side'><a href='#' class='items-cart'></a> <a href='#'\n" +
                                "                                                                                           class='items-like'></a></div>\n" +
                                "                        <a href='items.html?i='"+products[i].id+">\n" +
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

                            $(".items-cnt").html("").append(str);
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
    }

})()
