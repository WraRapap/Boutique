<?php
/**
 * 多欄位表格元件
 */
class Fieldset_Component extends CS_Component {
	
	
	/**
	 * 繪出元件
	 */
	public function render($attributes = array(), $next_line = true){

		
		// 取得資料集
		$this -> properties = $this -> getProperties();
		

		$fields = $this -> properties -> fields;

		$values = $this -> getValue();
		if(!is_array($values)){
			$items = json_decode($values);
			if(is_null($items)){
				$items = array();
			}	
		}
		else{
			$items = $values;
		}

		foreach($fields as $field){
			
			$properties = array();
			

		}

		$componentPath = $this -> config_env -> basePath . $this -> config_env -> componentPath;
		
?>
	

		<div id="<?php echo $this -> getVariable(); ?>_field_add" style="float:right;margin-bottom:5px;">
			<button class="btn btn-success fa fa-plus" type='button' title="新增資料"> 新增資料</button>
		</div>
		<br />
		<div style="clear:both;"></div>
		<div class="table-responsive no-padding">
			<table class="table table-hover">
				<thead>
				<tr>
					<th>&nbsp;</th>
					<?php foreach($fields as $field): ?>
					<th><?php echo $field["name"]; ?></th>
					<?php endforeach; ?>
					<th>操作</th>
				</tr>
				</thead>
				<tbody class="<?php echo $this -> getVariable(); ?>_items">

					<?php foreach($items as $item): ?>
					<tr class="<?php echo $this -> getVariable(); ?>_item">
						<td style="width:30px;cursor:pointer;" valign="middle" class="dropable noselect">
							<div class="fa fa-ellipsis-v" style="vertical-align: middle;"></div>
							<div class="fa fa-ellipsis-v" style="vertical-align: middle;"></div>
						</td>
						<?php foreach($fields as $field): ?>

						<?php 
							$path = $componentPath . "/" . strtolower($field["type"]) . ".php"; 
							include_once($path);
							
							$class_name = ucfirst($field["type"]) . "_Component";
							$component = new $class_name($field["name"], $this -> getVariable() . "_" . $field["variable"] . "[]",$item -> {$field["variable"]},'',$field["properties"],'');
						?>

						<td><?php $component -> render(array("class" => "form-control")); ?></td>
						<?php endforeach; ?>
						<td><button class="<?php echo $this -> getVariable(); ?>_delete btn btn-danger fa fa-trash" title="刪除資料" type="button"></button></td>
					</tr>

					<?php endforeach; ?>

					<tr class="<?php echo $this -> getVariable(); ?>_empty" style="display:none;">
						<td style="width:30px;cursor:pointer;" valign="middle" class="dropable noselect">
							<div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
							<div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
						</td>
						<?php foreach($fields as $field): ?>

						<?php 
							$path = $componentPath . "/" . strtolower($field["type"]) . ".php"; 
							include_once($path);

							$class_name = ucfirst($field["type"]) . "_Component";
							$component = new $class_name($field["name"],$this -> getVariable() . "_" . $field["variable"] . "[]",'','',$field["properties"],'');
							
						?>

						<td><?php $component -> render(array("class" => "form-control")); ?></td>
						<?php endforeach; ?>
						<td><button class="<?php echo $this -> getVariable(); ?>_delete btn btn-danger fa fa-trash" title="刪除資料" type="button"></button></td>
					</tr>
				</tbody>
			</table>
		</div>
<?php
	}


	/**
	 * 將 IO 的值設定進來
	 */
	public function setFromIO($method = "post", $xss = true){
		
		$this -> properties = $this -> getProperties();
		
		// print_r($this -> properties);



		$fields = $this -> properties -> fields;

		// 取得欄位變數名稱
		$field_variables = array();
		foreach($fields as $field){
			$field_variables[$field["variable"]] = $this -> getVariable() . "_" . $field["variable"];
		}

		// 取得總筆數
		$items = array();
		$count = 0;
		foreach($field_variables as $field_variable){
			$count = count($this -> tool_io -> $method($field_variable, $xss));	
			$items[$field_variable] = $this -> tool_io -> $method($field_variable, $xss);
		}

		$res = array();
		
		for($i=0 ; $i < $count-1 ; $i++){
			
			foreach($field_variables as $simple_field_name => $field_variable){
				$res[$i][$simple_field_name] = $items[$field_variable][$i];
			}
		}

		$this -> setValue(json_encode($res));
	}


	public function script(){

?>
	<script>
		$("document").ready(function(){
			// 拖曳順序
			$(".<?php echo $this -> getVariable(); ?>_items").sortable({
				handle : ".dropable"
			});

			// 新增欄位資訊
			$("#<?php echo $this -> getVariable(); ?>_field_add").click(function(){
				var new_object = $(".<?php echo $this -> getVariable(); ?>_empty").clone();
				$(".<?php echo $this -> getVariable(); ?>_empty").before(new_object);
	            new_object.addClass("<?php echo $this -> getVariable(); ?>_item").removeClass("<?php echo $this -> getVariable(); ?>_empty");
				new_object.show();
				
				<?php echo $this -> getVariable(); ?>_delete();
				
			});

			<?php echo $this -> getVariable(); ?>_delete();
		});

		// 刪除
		function <?php echo $this -> getVariable(); ?>_delete(){
			$(".<?php echo $this -> getVariable(); ?>_delete").unbind("click");
			$(".<?php echo $this -> getVariable(); ?>_delete").click(function(){
				$(this).parents(".<?php echo $this -> getVariable(); ?>_item").remove();                  
			});
		}


	</script>
<?php



	}
}
?>