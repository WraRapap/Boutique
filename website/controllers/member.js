
(function(){
    var PH = angular.module('PH');
    PH.controller('memberCtrl',memberCtrl);
    memberCtrl.$inject = ['$scope', 'DialogService'];
    function memberCtrl($scope,DialogService) {

        $("#editMember").click(function(){
            var flag= true;
            $("inner-info-top :text").each(function(){
                if($(this).val()==""){
                    showPrompt('會員', $(this).attr("placeholder")+"必填");
                    flag=false;
                    return;
                }
            })
            if(!flag){
                return false;
            }

            // if()
        })
    }

})()
