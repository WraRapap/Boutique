(function(){
    var PH = angular.module('PH');
    PH.controller('loginCtrl',loginCtrl);
    loginCtrl.$inject = ['$scope'];
    function loginCtrl($scope) {
        $scope.clickFlag=false;

        $scope.login=function () {
            $scope.clickFlag=true;

            if( $scope.lgform.$invalid){
                return false;
            }

            $.ajax({
                url:"/index.php/api/login",
                type:"post",
                data:{"email":$scope.email,"password":$scope.password,"code":$("#inputcode").val()},
                dataType:"json",
                success:function(data){
                   if(data.status==1){
                       if(GetQueryString("desti")!=null){
                           location.href=GetQueryString("desti")+".html"+location.search;
                       }else{
                           location.href="index.html";
                       }
                   }else{
                       showPrompt('登錄', data.msg);
                   }
                },
                error:function(){
                    showPrompt('登錄', "服務繁忙，請稍後重試");
                }
            });


       };

        $("#code").click(function(){
            $.ajax({
                url:"/index.php/api/getcode",
                type:"post",
                success:function(data){
                    data =$.trim( data);
                    $("#code").attr("src","http://fakeimg.pl/200x50/fff/000/?text="+data);
                }
            });
        });
    }

})()
