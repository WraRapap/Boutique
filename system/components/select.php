<?php 
class Select_Component extends CS_Component{

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}
		

		$properties = $this -> getProperties();

		// 取得資料集
		$datas = $this -> getDatasource();

		$value = $this -> getValue();

?>
	<select name="<?php echo $this -> getVariable(); ?>" <?php echo $attributes; ?>>

		<?php if(isset($properties -> nullitem) && $properties -> nullitem == "true"): ?>
		<option value="">請選擇</option>	
		<?php endif; ?>

		<?php foreach($datas as $data): ?> 
		<?php $selected = ($data[0] == $value) ? "selected":""; ?>
		<option value="<?php echo $data[0]; ?>" <?php echo $selected; ?>><?php echo $data[1]; ?></option>
		<?php endforeach; ?>
	<?php ?>
	</select>
<?php
	}

	public function getText(){
		$datas = $this -> getDatasource();
		$value = $this -> getValue();
		if(trim($value) == ""){
			return "";
		}
		else{
			return $datas[$value][1];
		}
	}

}

?>