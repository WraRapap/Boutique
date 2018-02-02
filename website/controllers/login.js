(function(){
    var PH = angular.module('PH');
    PH.controller('loginCtrl',loginCtrl);
    loginCtrl.$inject = ['$scope', 'DialogService'];
    function loginCtrl($scope,DialogService) {
        $scope.clickFlag=false;

        $scope.login=function () {
            $scope.clickFlag=true;

            if( $scope.lgform.$invalid){
                return false;
            }

            $.ajax({
                url:"/index.php/api/login",
                type:"post",
                data:{"email":$scope.email,"password":$scope.password},
                dataType:"json",
                success:function(data){
                   if(data.status==1){
                       if(GetQueryString("desti")!=null){
                           location.href=GetQueryString("desti")+".html"+location.search;
                       }else{
                           location.href="index.html";
                       }
                   }else{
                       DialogService.OpenMessage(1,
                           "登錄",
                           data.msg, null);
                   }
                },
                error:function(){
                    DialogService.OpenMessage(1,
                        "登錄",
                        "服務繁忙，請稍後重試", null);
                }
            })

       }
    }

})()
