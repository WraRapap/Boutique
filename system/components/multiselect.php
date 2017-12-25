<?php 
class Multiselect_Component extends CS_Component{

	private static $include_scripts = false;

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}
		

		// 取得資料集
		$datas = $this -> getDatasource();

		$values = $this -> getValue();

		if(!is_array($values)){
			$json = json_decode($values);
			if($json == ""){
				$values = explode(":", $values);
			}
			else{
				$values = $json;
			}
		}

		
?>
	<select name="<?php echo $this -> getVariable(); ?>[]" <?php echo $attributes; ?> multiple="multiple">
			<?php foreach($datas as $key => $data): ?> 
			<?php $selected = (in_array($key,$values)) ? "selected":""; ?>
			<option value="<?php echo $key; ?>" <?php echo $selected; ?>>　<?php echo $data[1]; ?></option>
			<?php endforeach; ?>
	<?php ?>
	</select>
<?php
	}

	public function setFromIO($method = "post", $xss = true){
		$this -> setValue(implode(":",$this -> tool_io -> $method($this -> getVariable(),$xss)));
	}

	public function getText(){
		// 資料來源
		$datas = $this -> getDatasource();
		$values = $this -> getValue();

		if(!is_array($values)){
			$json = json_decode($values);
			if($json == ""){
				$values = explode(":", $values);
			}
			else{
				$values = $json;
			}
		}
		
		$results = array();

		foreach($datas as $key => $data){
			if(in_array($key,$values)){
				$results[] = "<span class='label label-primary'>" . $data . "</span>";
			}
		}
		
		return implode(" ", $results);
	}


	public function script(){
		// 取得資料集
		$datas = $this -> getDatasource();

		if( !self::$include_scripts ){
			self::$include_scripts = true;
?>

	<link href="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/multiselect-2.4.4/jquery.multiselect.css" rel="stylesheet" type="text/css" />

	<script src="<?php echo $this -> config_env -> jsLibPath; ?>/jquery/multiselect-2.4.4/jquery.multiselect.js" type="text/javascript"></script>

<?php

		}

?>
	<script>
		$("document").ready(function(){
			$("select[name='<?php echo $this -> getVariable(); ?>[]']").multiselect({ 
				texts: { placeholder: '請選擇' } 
			});
		});
	</script>
<?php
	}

}

?>