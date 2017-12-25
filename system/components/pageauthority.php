<?php 
class Pageauthority_Component extends CS_Component{

	private $widgets = array();

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$pages = $this -> tool_database -> findAll("sys_page_class");

		
		$value = $this -> getValue();
		$authority = $this -> refineAuthorityObject(json_decode($value));
		
		

?>

	<div class="table-responsive no-padding">
		<table class="table table-hover">
			
			<thead>
				<tr>
					<th colspan="10">頁面管理權限</th>
				</tr>
			</thead>
			<tbody>
				
				

				<?php 
					foreach($pages as $page): 
						
						$perms = array();
						if(array_key_exists($page -> id,$authority)){
							$perms = $authority[$page -> id];
						}
						
				?>
				<tr class="<?php echo $this -> getVariable(); ?>_item" rel="<?php echo $page -> id; ?>">
					<td colspan="10"><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="<?php echo $page -> id; ?>" <?php echo (in_array($page -> id,$perms))?"checked":""; ?> /> <?php echo $page -> title; ?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
			<input type="hidden" name="<?php echo $this -> getVariable(); ?>" value="" />
		</table>
	</div>
	
<?php
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
			$(".<?php echo $this -> getVariable(); ?>_checkbox").iCheck({
				checkboxClass: 'icheckbox_flat-purple'
			});


			// 儲存
			$(".save").click(function(){

				var results = [];

				$(".<?php echo $this -> getVariable(); ?>_item").each(function(idx){
					var self = $(this);
					var widgetID = $(this).attr("rel");

					var widgetRes = []

					$(".<?php echo $this -> getVariable(); ?>_checkbox:checked", self).each(function(idx){
						widgetRes[idx] = $(this).val();
					});

					
					results[idx] = "{\"" + widgetID + "\":" + JSON.stringify(widgetRes) + "}";
				});
				
				$("input[name='<?php echo $this -> getVariable(); ?>']").attr("value", "[" + results.join() + "]");
			});
		});
	</script>
<?php



	}


	private function parseJsonContentToGetItemControl($widget){
		if($widget -> isExists()){
			$data = json_decode($widget -> jsonContent);
			if($data == ""){
				return array();
			}
			else{
				$res = explode(",", $data -> item_control);
				return $res;
			}
		}

		return array();
	}

	private function refineAuthorityObject($authorityObject){

		if($authorityObject == ""){
			return array();
		}

		$createArray = array();

		foreach($authorityObject as $object){
			$array = get_object_vars($object);
			foreach($array as $key => $value){
				$createArray[$key] = $value;
			}
		}

		return $createArray;
	}

}

?>