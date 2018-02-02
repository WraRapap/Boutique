(function(){
    var PH = angular.module('PH');
    PH.controller('itemCtrl',itemCtrl);
    itemCtrl.$inject = ['$scope', 'DialogService'];
    function itemCtrl($scope,DialogService) {
        $scope.color="";
        $scope.size="";
        $scope.productId=GetQueryString("i");
        $scope.cart=function(){
            if($scope.color== ""){
                DialogService.OpenMessage(1,
                    "商品選擇",
                    "顏色必填", null);
                return false;
            }

            if($scope.size== ""){
                DialogService.OpenMessage(1,
                    "商品選擇",
                    "尺寸必填", null);
                return false;
            }

            location.href="cart.html?p="+$("#price").val()+"&n="+$("#name").html()
                                        +"&c="+$("#color-collect").val()
                                        +"&s="+$("#size-collect").val()
                                        +"&i="+$scope.productId;
        }
    }

})()
