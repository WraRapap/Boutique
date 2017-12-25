<?php 
class Checkbox_Component extends CS_Component{
	public function render($attrs = array()){

		unset($attrs["class"]);

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
				$values = explode(",",$this -> getValue());
			}
			else{
				$values = $json;
			}
		}

?>
		<br />
		<?php foreach($datas as $data): ?>

		<?php $checked = (in_array($data[0], $values)) ? "checked":""; ?>

		<input type="checkbox" name="<?php echo $this -> getVariable(); ?>[]" value="<?php echo $data[0]; ?>" <?php echo $checked; ?> <?php echo $attributes; ?>/> <?php echo trim($data[1]); ?>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php endforeach; ?>
<?php
	}

	public function setFromIO($method = "post", $xss = true){
		$this -> setValue(implode(",",$this -> tool_io -> $method($this -> getVariable(),$xss)));
	}

	public function script(){

		if(is_array($this -> properties)){
			$this -> properties = (object)$this -> properties;
		}

		// $type = isset($this -> properties -> type) ? $this -> properties -> type : "flat";
		// $color = isset($this -> properties -> color) ? $this -> properties -> color : "blue";

?>
	<script>
		$("document").ready(function(){
			$("input[name='<?php echo $this -> getVariable(); ?>[]']").iCheck({
				checkboxClass: 'icheckbox_flat-purple'
			});
		});
	</script>
<?php



	}
}

?>