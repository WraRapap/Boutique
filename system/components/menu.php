<?php 
class Menu_Component extends CS_Component{

	private static $include_scripts = false;


	public function render($attrs = array()){

		$attributes = "";
		foreach($attrs as $key => $attrValue){
			$attributes .= " " . $key . "=\"" . $attrValue . "\"";
		}

		$pages = $this -> tool_database -> findAll("sys_page_class");

		$menus = json_decode($this -> getValue());

		if($menus == ""){
			$menus = array();
		}
		
		$render = "";
		foreach($menus as $menu){
			$render .= $this -> load_menu($pages, $menu);
		}

		$component = '<div id="container">
							<div style="float:right;"><button class="btn btn-success fa fa-plus-circle" title="新增選單" type="button" name="add_menu"></button></div>
							<br style="clear:both;" />
							' . $render . '
                      </div>
                         <input type="hidden" value="" name="' . $this -> getVariable() . '" />
                         <div class="menu_source callout callout-info hide">
							<h4 style="float:right;">
								<button class="btn btn-success fa fa-plus-circle" title="新增項目" type="button" name="add_item"></button>
								<button class="btn btn-danger fa fa-trash" title="刪除選單" type="button" name="del_menu" ></button>
							</h4>
	                        <h4 style="float:left;width:90%;">' . $this -> loadIconSelect('',true) . '<input type="text" value="" class="form-control input text hide"/></h4>
	                        <br style="clear:both;" />
	                    	<div class="sortable"></div>
						</div>
						<div class="item_source callout callout-warning hide">
							<h4 style="float:right;">
								<button class="btn btn-danger fa fa-trash" title="刪除項目" type="button" name="del_item" ></button>
							</h4>
		                    <h4 style="float:left;width:90%;">' . $this -> loadIconSelect('',true)  . $this -> loadPageSelect($pages, '',true) . '</h4>
		                    <br style="clear:both;" />
						</div>
                         ';
		echo $component;
	}

	public function load_menu($pages, $menu){
			
		$items = "";
		foreach($menu -> items as $item){
			$items .= $this -> load_items($pages, $item);
		}
		
		$render ='<div class="callout callout-info">
					<h4 style="float:right;">
						<button class="btn btn-success fa fa-plus-circle" title="新增項目" type="button" name="add_item"></button>
						<button class="btn btn-danger fa fa-trash" title="刪除選單" type="button" name="del_menu" ></button>
						</h4>
	                    <h4 style="float:left;width:90%;">' . $this -> loadIconSelect($menu -> icon) . '<input type="text" value="' . $menu -> title . '" class="form-control input text"/></h4>
                        <br style="clear:both;" />
                    	<div class="sortable">' . $items . '</div>
					</div>';
		return $render;
	}

	public function load_items($pages, $item){
			
		$render = '<div class="callout callout-warning">
					<h4 style="float:right;">
						<button class="btn btn-danger fa fa-trash" title="刪除項目" type="button" name="del_item" ></button>
					</h4>
	                <h4 style="float:left;width:90%;">' . $this -> loadIconSelect($item -> icon) . $this -> loadPageSelect($pages, $item -> page) . '</h4>
	                <br style="clear:both;" />
					</div>';
		return $render;
	}

	private function loadPageSelect($pages, $value, $hide = false){

		$hideClass = $hide ? "hide" : "";

		$pageSelect = "<select class='page-select input select {$hideClass}'>";
		foreach($pages as $page){
			$selected = ($value == $page -> id) ? "selected" : "";
			$pageSelect .= "<option value='" . $page -> id . "' " . $selected . ">" . $page -> title . "</option>";
		}
		$pageSelect .= "</select>";

		return $pageSelect;
	}

	private function loadIconSelect($value, $hide = false){


		$icons = $this -> config_icon -> icons;

		$hideClass = $hide ? "hide" : "";

		$iconSelect = "<select class='icon-select input {$hideClass}'>";

		foreach($icons as $key => $icon){
			$selected = ($key == $value) ? "selected" : "";
			$iconSelect .= "<option value='" . $key . "' " . $selected . ">" . $icon . "</option>";
		}
		$iconSelect .= "</select>";

		return $iconSelect;
	}

	public function script(){
if( !self::$include_scripts ){
               self::$include_scripts = true;
?>

<style>
	.callout {
		padding: 5px 30px 5px 15px;
		margin: 0 0 10px 0;
		border-left: 15px solid #eee;
	}

	.page-select {
		/* font-family:FontAwesome; */
		color:black;
		width:90%; 
		height:34px;
		margin-left:5px;
		/* font-size:30px; */
	}

	.icon-select {
		font-family:FontAwesome;
		color:black;
		/* width:90%; */
		height:34px;
		/* font-size:30px; */
		
	}

	.input {
		
		float:left;
	}

	.text {
		width:90%;
		margin-left:5px;
	}

</style>
<?php
		}

?>
<script type="text/javascript">
	$(document).ready(function(){
		$("#container").sortable();
		$(".sortable").sortable();
	});
	
	$("button[name='add_menu']").click(function(){
		var menu = $(".menu_source").clone();
		menu.removeClass("menu_source");
		menu.removeClass("hide");
		$(".hide",menu).removeClass("hide");
		$("#container").append(menu);
		$("button[name='del_menu']").unbind("click");
		$("button[name='del_menu']").click(del_menu);
		
		$("button[name='add_item']").unbind("click");
		$("button[name='add_item']").click(add_item);
	});
		
	
	
	function add_item(){
		var item = $(".item_source").clone();
		item.removeClass("item_source");
		item.removeClass("hide");
		$(".hide",item).removeClass("hide");
		$(this).parents(".callout").children(".sortable").append(item);
		
		$("button[name='del_item']").unbind("click");
		$("button[name='del_item']").click(del_item);
		
		$(".sortable").sortable();
	}
	
	function del_menu(){
		$(this).parents(".callout").remove();
	}
	
	function del_item(){
		$(this).parents(".callout").eq(0).remove();
	}
	
	$("button[name='add_item']").click(add_item);
	
	$("button[name='del_item']").click(del_item);
	
	$("button[name='del_menu']").click(del_menu);
	
	$("button[name='save']").click(function(){
		var menus = new Array();
		var index = -1;
		$(".input").not(".hide").each(function(){
			if($(this).hasClass("text")){
				
				var icon = $(this).siblings( ".icon-select" ).val();

				index ++;
				menus[index] = new Object();
				menus[index].icon = icon;
				menus[index].title = $(this).val();
				menus[index].items = [];
			}
			if($(this).hasClass("select")){
				
				var icon = $(this).siblings( ".icon-select" ).val();

				var count = menus[index].items.length;
				menus[index].items[count] = new Object();
				menus[index].items[count].icon = icon;
				menus[index].items[count].page = $(this).val();
			}});

			console.log(menus);	
		$("input[name='<?php echo $this -> getVariable(); ?>']").val(JSON.stringify(menus));

		
		return false;
	});
</script>
<?php



	}
}

?>