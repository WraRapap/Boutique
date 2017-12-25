<?php 
class Html_Component extends CS_Component{

	private static $include_scripts = false;

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}


		$component = '<textarea name="' . $this -> getVariable() . '" ' . $attributes . '>' . $this -> getValue() . '</textarea>';

		echo $component;
	}

	public function script(){
		// 取得資料集
		$datas = $this -> getDatasource();

		if( !self::$include_scripts ){
			self::$include_scripts = true;
?>

	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/tinymce-4.6.4/tinymce.min.js" type="text/javascript"></script>

<?php

		}

?>
	<script>
		tinymce.init({
		    selector: "textarea[name='<?php echo $this -> getVariable(); ?>']",
		    theme: "modern",
		    plugins: [
		        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
		        "searchreplace wordcount visualblocks visualchars code fullscreen",
		        "insertdatetime media nonbreaking save table contextmenu directionality",
		        "emoticons template paste textcolor colorpicker textpattern jbimages"
		    ],
		    toolbar1: "insertfile undo redo | code | bold italic underline | alignleft aligncenter alignright alignjustify | link image jbimages | bullist numlist outdent indent | print media | forecolor backcolor emoticons fontsizeselect",
		    file_browser_callback : RoxyFileBrowser,
		    image_advtab: true,
		    menubar : false,
		    relative_urls : false,
		    language : 'zh_TW',
		    height : 300,
			forced_root_block : false,

			fontsize_formats: "<?php for($i=8;$i<=60;$i++):?><?php echo $i;?>px <?php endfor;?>",
			formats: {
		        bold : {inline : 'b' },  
		        italic : {inline : 'i' },
		        underline : {inline : 'u'}
		    },
			setup: function (editor) {
		        editor.on('change', function () {
		            tinymce.triggerSave();
		        });
		    }
		});

		function RoxyFileBrowser(field_name, url, type, win) {
		  var roxyFileman = '<?php echo $this -> config_env -> jsLibPath; ?>/tinymce-4.6.4/plugins/fileman/index.html';
		  if (roxyFileman.indexOf("?") < 0) {     
		    roxyFileman += "?type=" + type;   
		  }
		  else {
		    roxyFileman += "&type=" + type;
		  }
		  roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
		  if(tinyMCE.activeEditor.settings.language){
		    roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
		  }

		  tinyMCE.activeEditor.windowManager.open({
		     file: roxyFileman,
		     title: '多媒體中心',
		     width: 600, 
		     height: 500,
		     resizable: "yes",
		     plugins: "media",
		     inline: "yes",
		     close_previous: "no"  
		  }, {     window: win,     input: field_name    });
		  return false; 
		}
	</script>
<?php
	}
}

?>