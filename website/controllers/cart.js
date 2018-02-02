(function(){
    var PH = angular.module('PH');
    PH.controller('cartCtrl',cartCtrl);
    cartCtrl.$inject = ['$scope', 'DialogService'];
    function cartCtrl($scope,DialogService) {
        $scope.color="";
        $scope.size="";
        $scope.banClick=false;
        $scope.totalfee=$("#totalfee").val();
        $scope.delCartProduct=function(uid){
            $scope.banClick=true;
            $.ajax({
                url:"/index.php/api/delCartProduct",
                type:"post",
                data:{"uid":uid},
                dataType:"json",
                success:function(data){
                    var showmsg="";
                    if(data.status==1){
                        showmsg="刪除成功";

                        var count = parseInt($("#"+uid+"count").html());
                        var fee = parseFloat($("#"+uid+"fee").val());
                        var totalcount = parseInt($("#totalcount").html());

                        $("#totalcount").html(totalcount-count);
                        $scope.totalfee-=fee;
                        $("#"+uid).remove();

                        $("#cartNum").html( parseInt($("#cartNum").html()) - 1);

                    }else{
                        showmsg = data.msg;
                    }
                    DialogService.OpenMessage(1,
                        "購物車",
                        showmsg, null);
                },
                error:function(){
                    DialogService.OpenMessage(1,
                        "購物車",
                        "服務繁忙，請稍後重試", null);
                }
        });
            $scope.banClick=false;
    }
    }

})()
