<?php 
class Radio_Component extends CS_Component{
	public function render($attrs = array()){

		unset($attrs["class"]);

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		// 取得資料集
		$datas = $this -> getDatasource();
		$value = $this -> getValue();


?>
		<br />
		<?php foreach($datas as $data): ?>

		<?php $checked = ($data[0] == $value) ? "checked":""; ?>

		<input type="radio" name="<?php echo $this -> getVariable(); ?>[]" value="<?php echo $data[0]; ?>" <?php echo $checked; ?> <?php echo $attributes; ?>/> <?php echo trim($data[1]); ?>&nbsp;&nbsp;&nbsp;&nbsp;
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
				radioClass: 'iradio_flat-purple'
			});
		});
	</script>
<?php



	}
}

?>