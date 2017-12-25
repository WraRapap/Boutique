<?php 
class Widgets_Component extends CS_Component{

	private $widgets = array();

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$widgets = $this -> tool_database -> findAll("sys_widget");
		$members = $this -> tool_database -> findAll("sys_member");

		
		$value = $this -> getValue();
		

?>
	<select name="<?php echo $this -> getVariable(); ?>" <?php echo $attributes; ?>>

	<?php foreach($widgets as $widget): ?>

		<?php $selected = ($widget -> id == $value) ? "selected":""; ?>
		<option value="<?php echo $widget -> id; ?>" <?php echo $selected; ?>><?php echo $widget -> title ; ?></option>
	
	<?php endforeach;?>

	<?php foreach($members as $member): ?>

		<?php $selected = ($member -> id == $value) ? "selected":""; ?>
		<option value="<?php echo $member -> id; ?>" <?php echo $selected; ?>><?php echo $member -> title ; ?></option>
	
	<?php endforeach;?>

	
	</select>
<?php
	}

	public function getText(){

		$widgets = $this -> tool_database -> findAll("sys_widget");
		
		foreach($widgets as $widget){
			if($widget -> id == $this -> getValue()){
				return $widget -> title;
			}
		}


		$members = $this -> tool_database -> findAll("sys_member");
		foreach($members as $member){
			if($member -> id == $this -> getValue()){
				return $member -> title;
			}
		}
		
		return "NaN";
	}

}

?>