<?php 
class Image_Component extends CS_Component{

	private static $include_scripts = false;

	public function render($attrs = array()){

		$value = $this -> getValue();


		$files = json_decode($value);

		if($files == ""){
			$files = array();
		}

		$properties = $this -> getProperties();

		

?>

	<style>
		.<?php echo $this -> getVariable(); ?>-image-thumb {
			float:left;
			width:150px;
			height:150px;
			background-color:white;
			border:1px solid #d2d6de;
			margin:5px;
			border-radius: 3px;
			box-shadow: 3px 3px 0px 0px rgba(0, 0, 0, 0.22);
			position: relative;
			overflow: hidden;
		}

		.<?php echo $this -> getVariable(); ?>-image-thumb .block{
			width:100%;
			height:100%;
			background-color:black;
			position: absolute;
			left:0px;
			top:0px;
			opacity: 0.5;
		}

		.<?php echo $this -> getVariable(); ?>-image-thumb .progress{
			position: absolute;
    		bottom: 0px;
    		width: 100%;
		}


	</style>
	<label for="<?php echo $this -> getVariable(); ?>_file_add" style="float:right;margin-bottom:5px;display: inline-block;" class="<?php echo $this -> getVariable(); ?>_file_add">
		<i class="btn btn-success fa fa-plus"> 新增圖片</i>
	</label>
	<input type="file" id="<?php echo $this -> getVariable(); ?>_file_add" name="<?php echo $this -> getVariable(); ?>_file_input[]" multiple style="display:none;">

	<br style="clear:both;" />

	<div class="<?php echo $this -> getVariable(); ?>-image-gallery table-responsive no-padding">

		<?php foreach($files as $file): ?>
		<div class="<?php echo $this -> getVariable(); ?>-image-thumb">
			<img src="<?php echo $this -> config_env -> baseUrl . $this -> config_env -> filePath . $file -> path; ?>">
			<input type="hidden" name="<?php echo $this -> getVariable(); ?>[]" value="<?php echo base64_encode(json_encode($file)); ?>" />
			<div style="clear:both;"></div>
		</div>
		<?php endforeach; ?>

		<div class="<?php echo $this -> getVariable(); ?>-image-empty" style="display:none;">
			<img src="">
			<div class="block"></div>
			<div class="progress sm ">
				<div class="progress-bar progress-bar-aqua bg-purple"></div>
			</div>
			<div style="clear:both;"></div>
		</div>

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

	public function getText(){
		$imageInfo = json_decode($this -> getValue());
		$baseUrl = $this -> config_env -> baseUrl . $this -> config_env -> filePath;
		if(count($imageInfo) > 0){
			return "<img src=\"" . $baseUrl . $imageInfo[0] -> path . "\" style=\"height:100px;\" />";
		}
		else{
			return "";
		}
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

	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/nailthumb/jquery.nailthumb.1.1.min.js" type="text/javascript"></script>

	<link rel="stylesheet" href="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/nailthumb/jquery.nailthumb.1.1.min.css">

	<script type="text/javascript" src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/jquery.image-close.jerry.js"></script>


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

		function <?php echo $this -> getVariable(); ?>_delete(me){
			var selfObject = me;

			var object = $("input[type='hidden']",selfObject);

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
							selfObject.remove();

							if($("input[name='<?php echo $this -> getVariable(); ?>[]']").length < max){
								$(".<?php echo $this -> getVariable(); ?>_file_add").show();
							}
						}
					}
				);
						
			});
		}

		<?php $properties = $this -> getProperties(); ?>
		var max = <?php echo isset($properties -> count)?((int)$properties -> count) : 1000000; ?>;

		
		$("document").ready(function(){

			// 拖曳順序
			$(".<?php echo $this -> getVariable(); ?>-image-gallery").sortable();

			$(".<?php echo $this -> getVariable(); ?>-image-thumb img").nailthumb({width:150,height:150,method:'resize',fitDirection:'center center'});

			$(".<?php echo $this -> getVariable(); ?>-image-thumb").image_close({
				close : <?php echo $this -> getVariable(); ?>_delete
			});
			

			if($("input[name='<?php echo $this -> getVariable(); ?>[]']").length >= max){
				$(".<?php echo $this -> getVariable(); ?>_file_add").hide();
			}
			
		

			var form = $("input[name='<?php echo $this -> getVariable(); ?>_file_input[]']").parents("form");
			var fu = form.fileupload({
		        url: '/index.php/api/fileupload',
		        autoUpload: false,
		        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
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
		        		
			        	var object = $(".<?php echo $this -> getVariable(); ?>-image-empty").clone();
						$(".<?php echo $this -> getVariable(); ?>-image-empty").before(object);
			            object.addClass("<?php echo $this -> getVariable(); ?>-image-thumb").removeClass("<?php echo $this -> getVariable(); ?>-image-empty");
						object.show();
						object.attr("data-file", file.name);

						var reader = new FileReader();
				        reader.onload = function(e) {
				        	var targetImage = $("img", object);
				            targetImage.attr('src', e.target.result);
				            targetImage.nailthumb({width:150,height:150,method:'resize',fitDirection:'center center'});
				            object.image_close({
								close : <?php echo $this -> getVariable(); ?>_delete
							});
				        }
				        reader.readAsDataURL(file);

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
			    	$("div[data-file='" + data.files[0].name + "'].<?php echo $this -> getVariable(); ?>-image-thumb .block").remove();
			    	$("div[data-file='" + data.files[0].name + "'].<?php echo $this -> getVariable(); ?>-image-thumb .progress").remove();

			    	$("div[data-file='" + data.files[0].name + "'].<?php echo $this -> getVariable(); ?>-image-thumb").append("<input type='hidden' name='<?php echo $this -> getVariable(); ?>[]' value='" + data.result + "' />");

			    	if($("input[name='<?php echo $this -> getVariable(); ?>[]']").length >= max){
						$(".<?php echo $this -> getVariable(); ?>_file_add").hide();
					}
			       
			    }
	    	});


    	});
	</script>
<?php
	}
}

?>