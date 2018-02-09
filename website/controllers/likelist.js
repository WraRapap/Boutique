$(function(){
   $(".member-btn-delete").click(function(){
       var i =$(this).attr("i");
       $.ajax({
           url:"/index.php/api/delLike",
           type:"post",
           data:{"i":i},
           dataType:"json",
           success:function(data){
               if(data.status==1){
                   $("ul[i="+i+"]").remove();
               }
               else if(data.status==-1){
                   showPrompt('追蹤清單', "請先登錄");
               }
               else{
                   showPrompt('追蹤清單', data.msg);
               }
           },
           error:function(){
               showPrompt('追蹤清單', "服務繁忙，請稍後重試");
           }
       })
   })

});
function buy(id){
    location.href="item.html?i="+id;
}