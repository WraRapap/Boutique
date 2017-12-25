<?php 
class Widgetlist_Component extends CS_Component{

	private $models = array();

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		
		
		$value = $this -> getValue();
		

?>
	<select name="<?php echo $this -> getVariable(); ?>" <?php echo $attributes; ?>>

	<?php foreach($this -> config_widget -> groups as $group): ?>

	<optgroup label="<?php echo $group["title"]; ?>">

		<?php foreach($group["widgets"] as $widget): ?> 

		<?php $selected = ($widget["variable"] == $value) ? "selected":""; ?>
		<option value="<?php echo $widget["variable"]; ?>" <?php echo $selected; ?>><?php echo $widget["title"]; ?></option>
		<?php endforeach; ?>
	

	</optgroup>
	
	<?php endforeach;?>

	
	</select>
<?php
	}

	public function getText(){
		foreach($this -> config_widget -> groups as $group){
			foreach($group["widgets"] as $widget){
				if($widget["variable"] == $this -> getValue()){
					return $widget["title"];
				}
			}
		}

		return "NaN";
	}

}

?>