<?php 
class Componentlist_Component extends CS_Component{

	private static $include_scripts = false;

	private $models = array();

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}
		
		$value = $this -> getValue();
		

?>
	<select name="<?php echo $this -> getVariable(); ?>" <?php echo $attributes; ?>>
	<?php foreach($this -> config_component -> groups as $group): ?>

		<optgroup label="<?php echo $group["title"]; ?>">

			<?php foreach($group["components"] as $component): ?> 
			<?php $selected = ($component["variable"] == $value) ? "selected":""; ?>
			<option value="<?php echo $component["variable"]; ?>" <?php echo $selected; ?>><?php echo $component["title"]; ?></option>
			<?php endforeach; ?>
		</optgroup>

	<?php endforeach;?>
	<?php ?>
	</select>
<?php
	}

}

?>