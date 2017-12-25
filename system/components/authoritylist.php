<?php 
class Authoritylist_Component extends CS_Component{

	private $authorities = array();

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$authorities = $this -> tool_database -> findAll("sys_authority");
		

		
		$value = $this -> getValue();
		

?>
	<select name="<?php echo $this -> getVariable(); ?>" <?php echo $attributes; ?>>

		<option value="">請選擇</option>
		
		<?php foreach($authorities as $authority): ?>
		<?php $selected = ($authority -> id == $value) ? "selected":""; ?>
		<option value="<?php echo $authority -> id; ?>" <?php echo $selected; ?>><?php echo $authority -> title ; ?></option>
		<?php endforeach;?>

	</select>
<?php
	}

	public function getText(){

		$authorities = $this -> tool_database -> findAll("sys_authority");
		

		
		foreach($authorities as $authority){
			if($authority -> id == $this -> getValue()){
				return $authority -> title;
			}
		}
		
		return "無";
	}

}

?>