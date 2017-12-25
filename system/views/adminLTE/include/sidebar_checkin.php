	<div class="user-panel">
      <div class="datetime" style="text-align:center;width:100%;font-size:20px;">
      	<div class="current-time" style="font-size:40px;"></div>
      	<div class="current-date"></div>
      </div>
      <div class="checkin" style="text-align:center;width:100%;">
      	<hr />
        <a class="btn btn-info btn-xs" href="/index.php/admin/page?page_id=15a089c910059a&type=checkin" style="color:white;"><i class="fa fa-clock-o"></i> 上班打卡</a> 
        <a class="btn btn-warning btn-xs" href="/index.php/admin/page?page_id=15a089c910059a&type=checkout" style="color:white;"><i class="fa fa-clock-o"></i> 下班打卡</a>
      </div>
    </div>
    <script>
    $("document").ready(function(){
    	showDateTime();
    	setInterval(showDateTime,1000);

	});
    function showDateTime(){
    	var dNow = new Date();
	    var date = dNow.getFullYear() + "/" + (dNow.getMonth()+1) + '/' + dNow.getDate();
	    var time = ("0" + dNow.getHours()).slice(-2) + ':' + ("0" + dNow.getMinutes()).slice(-2) + ':' + ("0" + dNow.getSeconds()).slice(-2);
	    var week = ["日","一","二","三","四","五","六"];
	    $(".current-date").html(date + "( " + week[dNow.getDay()] + " )");
	    $(".current-time").html(time);
    }
    </script>


