<?php 
class File_Component extends CS_Component{

	private static $include_scripts = false;

	public function render($attrs = array()){

		$value = $this -> getValue();


		$files = json_decode($value);

		if($files == ""){
			$files = array();
		}

?>

	<label for="<?php echo $this -> getVariable(); ?>_file_add" style="float:right;margin-bottom:5px;display: inline-block;">
		<i class="btn btn-success fa fa-plus"> 新增檔案</i>
	</label>
	<input type="file" id="<?php echo $this -> getVariable(); ?>_file_add" name="<?php echo $this -> getVariable(); ?>_file_input[]" multiple style="display:none;">

	<br style="clear:both;" />

	<div class="table-responsive no-padding">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>&nbsp;</th>
					
					<th>檔名</th>
					<th>大小</th>
					<th>狀態</th>
					<th>操作</th>
				</tr>
				</thead>
				<tbody class="<?php echo $this -> getVariable(); ?>_items" style="max-height:500px;overflow-y:scroll;">


					<?php foreach($files as $file): ?>
					<tr class="<?php echo $this -> getVariable(); ?>_item">
						<td style="width:30px;cursor:pointer;" valign="middle" class="dropable">
							<div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
							<div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
						</td>
						<td class="title"><?php echo $file -> fileName; ?></td>
						<td class="size"><?php echo $this -> tool_utility -> bytesToSize($file -> size); ?></td>
						<td class="status">已上傳<input type='hidden' name='<?php echo $this -> getVariable(); ?>[]' value='<?php echo base64_encode(json_encode($file)); ?>' /></td>
						<td><button class="<?php echo $this -> getVariable(); ?>_delete btn btn-danger fa fa-trash" title="刪除資料" type="button"></button></td>
					</tr>
					<?php endforeach; ?>






					<tr class="<?php echo $this -> getVariable(); ?>_empty" style="display:none;">
						<td style="width:30px;cursor:pointer;" valign="middle" class="dropable">
							<div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
							<div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
						</td>
						<td class="title"></td>
						<td class="size"></td>
						<td class="status"></td>
						<td><button class="<?php echo $this -> getVariable(); ?>_delete btn btn-danger fa fa-trash" title="刪除資料" type="button" style="display:none;"></button></td>
					</tr>
				</tbody>
			</table>
		</div>
<?php
	}

	/**
	 * 將 IO 的值設定進來
	 */
	public function setFromIO($method = "post", $xss = true){


		$beSaveFiles = array();

		$files = $this -> tool_io -> post($this -> getVariable(),$xss);

		foreach($files as $file){

			$fileObject = json_decode(base64_decode($file));
			
			if($fileObject == ""){
				continue;
			}
		
			
			// 新上傳的檔案
			if(substr($fileObject -> path,0,5) == "/temp"){

				// 從暫存資料夾移到正式資料夾
				$filePath = substr($fileObject -> path,5);
				$baseUrl = $this -> config_env -> basePath . $this -> config_env -> filePath;
				$sourcePath = $baseUrl . $fileObject -> path;
				$targetPath = $baseUrl . $filePath;
				rename($sourcePath, $targetPath);

				$beSaveFiles[] = array(
					"id" => uniqid(),
					"path" => $filePath,
					"fileName" => $fileObject -> fileName,
					"size" => $fileObject -> size,
					"ext" => $fileObject -> ext,
					"createTime" => date("Y-m-d H:i:s")
				);
			}
			else{
				$beSaveFiles[] = $fileObject;
			}
		}

		$this -> setValue(json_encode($beSaveFiles));
	}

	public function script(){
		// 取得資料集
		$datas = $this -> getDatasource();


		if( !self::$include_scripts ){
			self::$include_scripts = true;
?>

	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/fileupload/vendor/jquery.ui.widget.js" type="text/javascript"></script>

	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/fileupload/jquery.iframe-transport.js" type="text/javascript"></script>

	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/fileupload/jquery.fileupload.js" type="text/javascript"></script>


<?php

		}

?>
	<script>

		function <?php echo $this -> getVariable(); ?>_bytesToSize(bytes) {
		   var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
		   if (bytes == 0) return '0 Byte';
		   var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
		   return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
		};

		// 刪除
		function <?php echo $this -> getVariable(); ?>_delete(){
			$(".<?php echo $this -> getVariable(); ?>_delete").unbind("click");
			$(".<?php echo $this -> getVariable(); ?>_delete").click(function(){


				var selfObject = $(this);

				var object = $(this).parents("td").parents("tr.<?php echo $this -> getVariable(); ?>_item").find("input[type='hidden']");

				var form = $("input[name='<?php echo $this -> getVariable(); ?>_file_input[]'").parents("form");


	
				widget_confirm("是否刪除此檔案?","warning","",function(){
					widget_post(
						"<?php echo $this -> config_env -> baseUrl; ?>/index.php/api/fileDelete", 
						{
							"signed": form.attr("data-signed"),
							"variable": '<?php echo $this -> getVariable(); ?>',
							"object":object.val()
						}, 
						function(data){
							var data = JSON.parse(data);
							
							if(data.result == "yes"){
								selfObject.parents(".<?php echo $this -> getVariable(); ?>_item").remove();
							}
						}
					);
					
				});


				                  
			});
		}

		$("document").ready(function(){

			// 拖曳順序
			$(".<?php echo $this -> getVariable(); ?>_items").sortable({
				handle : ".dropable"
			});

			var form = $("input[name='<?php echo $this -> getVariable(); ?>_file_input[]'").parents("form");
			var fu = form.fileupload({
		        url: '/index.php/api/fileupload',
		        autoUpload: false,
		        // acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		        maxFileSize: 1000000000,
		        // Enable image resizing, except for Android and Opera,
		        // which actually support image resizing, but fail to
		        // send Blob objects via XHR requests:
		        disableImageResize: /Android(?!.*Chrome)|Opera/
		            .test(window.navigator.userAgent),
		        previewMaxWidth: 100,
		        previewMaxHeight: 100,
		        previewCrop: true,
		        add: function (e, data) {
		        	$.each(data.files, function(index, file){

			        	var object = $(".<?php echo $this -> getVariable(); ?>_empty").clone();
						$(".<?php echo $this -> getVariable(); ?>_empty").before(object);
			            object.addClass("<?php echo $this -> getVariable(); ?>_item").removeClass("<?php echo $this -> getVariable(); ?>_empty");
						object.show();

						object.attr("data-file", file.name);
						$(".title", object).html(file.name);
						$(".size", object).html(<?php echo $this -> getVariable(); ?>_bytesToSize(file.size));
						$(".status", object).html("<div class=\"progress sm \" data-file=\"" + file.name +"\"><div class=\"progress-bar progress-bar-aqua bg-purple\"></div></div>");

						<?php echo $this -> getVariable(); ?>_delete();
		        	});
		        	
		        	data.submit();
		        },
		        progress: function (e, data) {

			        var progress = parseInt(data.loaded / data.total * 100, 10);
			        
			        $("div[data-file='" + data.files[0].name + "'] .progress-bar").css(
			            'width', progress + '%'
			        );

			        
			    },
			    done : function(e, data){
			    	$(".<?php echo $this -> getVariable(); ?>_item div[data-file='" + data.files[0].name + "']").wrap("<div style='color:green;font-weight:bold;'>傳送完成<input type='hidden' name='<?php echo $this -> getVariable(); ?>[]' value='" + data.result + "' /></div>");
			        $(".<?php echo $this -> getVariable(); ?>_item div[data-file='" + data.files[0].name + "']").remove();
			        $(".<?php echo $this -> getVariable(); ?>_item tr[data-file='" + data.files[0].name + "'] .<?php echo $this -> getVariable(); ?>_delete").show();
			    }
	    	});

	    	<?php echo $this -> getVariable(); ?>_delete();


    	});
	</script>
<?php
	}
}

?>