<?php 
class Authority_Component extends CS_Component{

	private $widgets = array();

	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$widgets = $this -> tool_database -> findAll("sys_widget");
		$members = $this -> tool_database -> findAll("sys_member");
		$pages = $this -> tool_database -> findAll("sys_page_class");

		
		$value = $this -> getValue();
		$authority = $this -> refineAuthorityObject(json_decode($value));
		
		

?>

	<div class="table-responsive no-padding">
		<table class="table table-hover">
			<thead>
			<tr>
				<th>功能名稱</th>
				<th>新增</th>
				<th>修改</th>
				<th>刪除</th>
				<th>檢視</th>
				<th>複製</th>
				<th>排序</th>
				<th>上下架</th>
				<th>匯出</th>
				<th>列印</th>
			</tr>
			</thead>
			<tbody class="<?php echo $this -> getVariable(); ?>_items">
				<?php 
					foreach($widgets as $widget): 
						$item_control = $this -> parseJsonContentToGetItemControl($widget);
						$perms = array();
						if(array_key_exists($widget -> id,$authority)){
							$perms = $authority[$widget -> id];
						}
				?>
				<tr class="<?php echo $this -> getVariable(); ?>_item" rel="<?php echo $widget -> id; ?>">
					<td><?php echo $widget -> title; ?></td>
				
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="ADD" <?php echo (in_array("ADD",$item_control))?"":"disabled"; ?> <?php echo (in_array("ADD",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="FIX" <?php echo (in_array("FIX",$item_control))?"":"disabled"; ?> <?php echo (in_array("FIX",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="DEL" <?php echo (in_array("DEL",$item_control))?"":"disabled"; ?> <?php echo (in_array("DEL",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="VIEW" <?php echo (in_array("VIEW",$item_control))?"":"disabled"; ?> <?php echo (in_array("VIEW",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="CLONE" <?php echo (in_array("CLONE",$item_control))?"":"disabled"; ?> <?php echo (in_array("CLONE",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="SORT" <?php echo (in_array("SORT",$item_control))?"":"disabled"; ?> <?php echo (in_array("SORT",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="PUBLISH" <?php echo (in_array("PUBLISH",$item_control))?"":"disabled"; ?> <?php echo (in_array("PUBLISH",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="EXPORT" <?php echo (in_array("EXPORT",$item_control))?"":"disabled"; ?> <?php echo (in_array("EXPORT",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="PRINT" <?php echo (in_array("PRINT",$item_control))?"":"disabled"; ?> <?php echo (in_array("PRINT",$perms))?"checked":""; ?> /></td>

				</tr>

				<?php endforeach; ?>


				<?php 
					foreach($members as $member): 
						$item_control = $this -> parseJsonContentToGetItemControl($member);
						$perms = array();
						if(array_key_exists($member -> id,$authority)){
							$perms = $authority[$member -> id];
						}
				?>
				<tr class="<?php echo $this -> getVariable(); ?>_item" rel="<?php echo $member -> id; ?>">
					<td><?php echo $member -> title; ?></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="ADD" <?php echo (in_array("ADD",$item_control))?"":"disabled"; ?> <?php echo (in_array("ADD",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="FIX" <?php echo (in_array("FIX",$item_control))?"":"disabled"; ?> <?php echo (in_array("FIX",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="DEL" <?php echo (in_array("DEL",$item_control))?"":"disabled"; ?> <?php echo (in_array("DEL",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="VIEW" <?php echo (in_array("VIEW",$item_control))?"":"disabled"; ?> <?php echo (in_array("VIEW",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="CLONE" <?php echo (in_array("CLONE",$item_control))?"":"disabled"; ?> <?php echo (in_array("CLONE",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="SORT" <?php echo (in_array("SORT",$item_control))?"":"disabled"; ?> <?php echo (in_array("SORT",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="PUBLISH" <?php echo (in_array("PUBLISH",$item_control))?"":"disabled"; ?> <?php echo (in_array("PUBLISH",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="EXPORT" <?php echo (in_array("EXPORT",$item_control))?"":"disabled"; ?> <?php echo (in_array("EXPORT",$perms))?"checked":""; ?> /></td>
					<td><input type="checkbox" class="<?php echo $this -> getVariable(); ?>_checkbox" value="PRINT" <?php echo (in_array("PRINT",$item_control))?"":"disabled"; ?> <?php echo (in_array("PRINT",$perms))?"checked":""; ?> /></td>

				</tr>

				<?php endforeach; ?>

			</tbody>
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