(function(){
    var PH = angular.module('PH');
    PH.controller('signinCtrl',signinCtrl);
    signinCtrl.$inject = ['$scope', 'DialogService'];
    function signinCtrl($scope,DialogService) {
        $scope.clickFlag=false;

        $scope.register=function () {
            $scope.clickFlag=true;

            if($scope.snform.$invalid){
                return false;
            }

            if($scope.password!=$scope.confirmpwd){
                DialogService.OpenMessage(1,
                    "註冊",
                    "密碼 與 確認密碼不符", null);
                return false;
            }

            $.ajax({
                url:"/index.php/api/signin",
                type:"post",
                data:{"account":$scope.account,"email":$scope.email,"password":$scope.password,"mt4":$scope.mt4,"confirmpwd":$scope.confirmpwd},
                dataType:"json",
                success:function(data){
                   if(data.status==1){
                       if(GetQueryString("desti")!=null){
                           location.href=GetQueryString("desti")+".html"+location.search;
                       }else{
                           location.href="login.html";
                       }
                   }else{
                       DialogService.OpenMessage(1,
                           "註冊",
                           data.msg, null);
                   }
                },
                error:function(){
                    DialogService.OpenMessage(1,
                        "註冊",
                        "服務繁忙，請稍後重試", null);
                }
            })

       }
    }

})()
