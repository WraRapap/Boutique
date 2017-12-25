/**
 * 零件載入
 * @param string id
 */
function widget_load(id, action, feed_data, feedback = ""){

	// Loading 效果

	$("#" + id + " .box").append("<div class=\"overlay\"><i class=\"fa fa-refresh fa-spin\"></i></div>");

	var url = "/index.php/widget/main";

	var params = "?action=" + action + "&widget=" + id;

	if(feedback == ""){
		if(typeof feed_data === "string"){
			params += "&" + feed_data;
			feed_data = {};
		}

		widget_post((url + params),feed_data, function(data){
	    	$("#" + id).html(data);
		});

	}
	else{
		// $.post((url + params),feed_data, feedback);
		widget_post((url + params),feed_data, feedback);
	}
}

function widget_post(url, data, feedback, id = ""){

	if(id != ""){
		// $("#" + id).append("<div class=\"overlay\"><i class=\"fa fa-refresh fa-spin\"></i></div>");
	}
	$.ajax({
		url: url,
		async: true,
		data: data,
        type: 'POST',
        success: function(data){
        	feedback(data);
        }
   	});
}

function widget_get_form_data($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};

    $.map(unindexed_array, function(n, i){
    	var parts = n['name'].split("[");
    	if(parts.length > 1){
    		if(indexed_array[parts[0]] == 'undefined' || indexed_array[parts[0]] == undefined){
    			indexed_array[parts[0]] = [];
    		}
    		indexed_array[parts[0]].push(n['value']);
    	}
    	else{
    		indexed_array[n['name']] = n['value'];
    	}
        
    });

    return indexed_array;
}

function test_function(data){
	console.log(data);
}

function widget_init(id, action = "", feed_data = []){
	widget_load(id,action,feed_data);
}

function widget_alert(text, status="success", description = ""){
	swal(text,description,status);
}

function widget_confirm(text, status="success", description = "", confirm_function = null, cancel_function = null, confirm_text="確定", cancel_text="取消"){

	swal({
		title: text,
		text: description,
		type: status,
		showCancelButton: true,
		confirmButtonText: confirm_text,
		cancelButtonText: cancel_text,
		closeOnConfirm: (confirm_function == null),
		closeOnCancel: (cancel_function == null)
	},
	function(isConfirm){
		if (isConfirm) {
			if(confirm_function != null){
				confirm_function();
				swal.close();
			}
		}
		else {
			if(cancel_function != null){
				cancel_function();
				swal.close();
			}
		}
	});
}