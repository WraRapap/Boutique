(function(){
    var PH = angular.module('PH');
    PH.controller('cartCtrl',cartCtrl);
    cartCtrl.$inject = ['$scope', 'DialogService'];
    function cartCtrl($scope,DialogService) {
        $(".country").html($("#country").find(":selected").html());
        $(".delivery").html($("#delivery").find(":selected").html());
        $(".payment").html($("#payment").find(":selected").html());

        $scope.color="";
        $scope.size="";
        $scope.banClick=false;
        $scope.clickFlag=false;
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
        };

        $("#confirmOrder").click(function(){
            if($("ul.check-list li").length<1){
                DialogService.OpenMessage(1,
                    "確認訂單",
                    "购物车是空的哦", null);
                return false;
            }

            if(!$("#agree").prop("checked")){
                DialogService.OpenMessage(1,
                    "確認訂單",
                    "請先同意服務條款和退換貨政策", null);
                return false;
            }

            var go=true;
            $(".cart-before :text").each(function(){
                if($(this).val()==''){
                    go=false;
                    DialogService.OpenMessage(1,
                        "確認訂單",
                        $(this).attr("placeholder")+"必填", null);
                    return false;
                }
            });
            if(!go){
                return false;
            }

            $(".recName").html($("#recName").val());
            $(".recPhone").html($("#recPhone").val());
            $(".address").html($("#address").val());
            $("div.cart-after").removeClass("cart-hidden");
            $("div.cart-before").addClass("cart-hidden");


        });
        $("#sendOrder").click(function(){
            $(this).css("disabled","disabled");

            var products =[];
            $("ul.check-list li").each(function(){
                products.push({"id":$(this).attr("pid"),"color":$(this).find("[c]").attr("c"),"size":$(this).find("[s]").attr("s")});
            });

            $.ajax({
                type:"post",
                data:{"products":JSON.stringify(products),
                    "name":$("#name").val(),
                    "email":$("#email").val(),
                    "phone":$("#phone").val(),
                    "recName":$("#recName").val(),
                    "recPhone":$("#recPhone").val(),
                    "areacode":$("#areacode").val(),
                    "city":$("#city").val(),
                    "address":$("#address").val(),
                    "country":$("#country").val(),
                    "delivery":$("#delivery").val(),
                    "payment":$("#payment").val(),
                    "remark":$("#remark").val()},
                url:"/index.php/api/confirmOrder",
                dataType:"json",
                success:function(data){
                    if(data.status==1){
                        location.href="order.html";
                    }else if(data.status==0){
                        $("#"+data.id).remove();
                        DialogService.OpenMessage(1,
                            "訂單送出",
                            data.msg, null);

                    }
                    else if(data.status==2){
                        DialogService.OpenMessage(1,
                            "訂單送出",
                            $("#"+data.id).attr("placeholder")+"必填", null);

                    }
                    else{
                        DialogService.OpenMessage(1,
                            "訂單送出",
                            data.msg, null);
                    }
                },
                error:function(){
                    DialogService.OpenMessage(1,
                        "訂單送出",
                        "系統繁忙，請稍後重試", null);
                }
            });
        });
        $(".cart-before select").change(function(){
            $("."+$(this).attr("id")).html($(this).find(":selected").html());
        });

        $("#same").click(function(){
            if($(this).find(":checkbox").prop("checked")){
                $("#recName").val($("#name").val());
                $("#recPhone").val($("#phone").val());
            }else{
                $("#recName").val("");
                $("#recPhone").val("");
            }
        })
    };
})()
