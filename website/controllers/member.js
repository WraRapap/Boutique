
(function(){
    var PH = angular.module('PH');
    PH.controller('memberCtrl',memberCtrl);
    memberCtrl.$inject = ['$scope', 'DialogService'];
    function memberCtrl($scope,DialogService) {

        $("#editMember").click(function(){
            $(this).attr("disabled","disabled");
            var flag= true;
            var data={};
            $(".inner-info-top :text").each(function(){
                if($(this).val()==""){
                    showPrompt('會員', $(this).attr("placeholder")+"必填");
                    flag=false;
                    return false;
                }
                data[$(this).attr("id")]=$(this).val();
            });
            if(!flag){
                $(this).removeAttr("disabled");
                return false;
            }

            data["country"]=$("#country").val();

            if($("#oldPwd").val()!="" || $("#newPwd").val()!="" || $("#reNewPwd").val()!=""){
                if($("#newPwd").val()!=$("#reNewPwd").val()){

                    showPrompt('會員', "新密碼兩次輸入不一致");
                    $(this).removeAttr("disabled");
                    return false;

                }

                $(".pwd-block :text").each(function(){
                    if($(this).val()==""){

                        showPrompt('會員', "密码必填");
                        flag=false;
                        return false;
                    }
                    data[$(this).attr("id")]=$(this).val();
                });
                if(!flag){
                    $(this).removeAttr("disabled");
                    return false;
                }


            }

            $.ajax({
                url:"/index.php/api/member",
                type:"post",
                data:data,
                dataType:"json",
                success:function(data){
                    if(data.status==1){
                            location.href="member.html";
                    }else{
                        showPrompt('會員',data.msg);

                    }
                },
                error:function(){

                    showPrompt('會員',"服務繁忙，請稍後重試");
                }
            });

            $(this).removeAttr("disabled");
        })
    }

})()
