<?php 
class Toggle_Component extends CS_Component{

	private static $include_scripts = false;

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$value = $this -> getValue();
		$checked = ($value == "Y") ? "checked" : "";


		$component = '<br /><input type="checkbox" name="' . $this -> getVariable() . '" value="Y" ' . $checked . ' />';

		echo $component;
	}

	public function getText(){
		$datas = $this -> getDatasource();
		return ($this -> getValue() == "Y") ? ("<span class=\"toggle-yes\">" . $datas[0][0] . "</span>") : ("<span class=\"toggle-no\">" . $datas[1][0] . "</span>");
	}

	public function script(){
		// 取得資料集
		$datas = $this -> getDatasource();

		if( !self::$include_scripts ){
			self::$include_scripts = true;
?>
	<link href="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/jquery-ui-1.12.1/jquery-ui.min.js" type="text/javascript"></script>

	<link href="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/switch-button/jquery.switchButton.css" rel="stylesheet" type="text/css" />
	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/switch-button/jquery.switchButton.js" type="text/javascript"></script>

<?php

		}

?>
	<script>
		$("document").ready(function(){
			var component = $("input[name='<?php echo $this -> getVariable(); ?>']");
			
			component.switchButton({
				on_label: '<?php echo $datas[0][0]; ?>',
          		off_label: '<?php echo $datas[1][0]; ?>',
          		height:23,
          		width:60,
          		button_width:30
       		}).change(function(){
				component.attr("checked", $(this).prop("checked"));
			});;
       		// component.siblings("ins").remove();
       		// component.parents(".icheckbox_minimal").removeClass("icheckbox_minimal");
		});
	</script>
<?php
	}
}

?>