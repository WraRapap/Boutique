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
                showPrompt('商品選擇', "顏色必填");
                return false;
            }

            if($scope.size== ""){
                showPrompt('商品選擇', "尺寸必填");
                return false;
            }

            location.href="cart.html?p="+$("#price").val()+"&n="+$("#name").html()
                                        +"&c="+$("#color-collect").val()
                                        +"&s="+$("#size-collect").val()
                                        +"&i="+$scope.productId;
        }
    }

})()
