
<!-- general form elements -->
<div class="box box-primary">
	<!-- form start -->
	<form name="editform" method="post" enctype="multipart/form-data" data-signed="<?php echo base64_encode(json_encode(array("id" => $this -> data -> id , "data_source" => $this -> widgetMeta -> data_source))); ?>"> 
		<div class="box-header">
    	
    		<div class="box-tools pull-left" style="position: static;">
        		<div class="btn-group" style="margin-right:20px;">
            		<button class="save btn btn-success fa fa-save" title="儲存" type="button" name="save"> 儲存</button>
				</div>
			
				<div class="btn-group" style="margin-right:20px;">
            		<button class="back btn bg-orange fa  fa-mail-reply" title="不儲存回清單" type="button" name="back_to_rows"> 不儲存回清單</button>

            
                	<button class="delete btn btn-danger fa fa-trash" title="刪除" type="button" name="delete"> 刪除</button>
            
				</div>
			</div>
        	
        	<div class="box-tools pull-right" style="position: static;">
		      <ol class="breadcrumb">
		        <li><i class="fa fa-tags"></i> <?php echo $this -> widgetMeta -> title; ?></li>
		      </ol>
		    </div>
			
		
		</div><!-- /.box-header -->
        
    	<div class="box-body">
    		<?php foreach($this -> data as $key => $component): ?>
    		<?php if(!is_object($component)){continue;} ?>
            <div class="form-group">
            	<?php $component -> getLabel(); ?>
				<?php $component -> render(array("class" => "form-control")); ?>
			</div>
			<?php endforeach; ?>
		</div>

		<div class="box-footer">
			<div class="box-tools pull-left" style="position: static;">
            	<div class="btn-group" style="margin-right:20px;">
                	<button class="save btn btn-success fa fa-save" title="儲存" type="button" name="save"> 儲存</button>
				</div>
				<div class="btn-group" style="margin-right:20px;">
                	<button class="back btn bg-orange fa  fa-mail-reply" title="不儲存回清單" type="button" name="back"> 不儲存回清單</button>
                
                	<button class="delete btn btn-danger fa fa-trash" title="刪除" type="button" name="delete"> 刪除</button>
				</div>
			</div>
		</div>
		<input type="hidden" name="id" value="<?php echo $this -> data -> id; ?>" />
		<input type="hidden" name="parentID" value="<?php echo $this -> parentID; ?>" />
	</form>
</div><!-- /.box -->
<?php 
	foreach($this -> data as $key => $component){
		if(!is_object($component)){continue;}
		$component -> script();
	}
?>
          
<script>
	$("document").ready(function(){
		
		$("#<?php echo $this -> widgetId; ?> .save").click(function(){
			widget_load('<?php echo $this -> widgetId; ?>','classSave',widget_get_form_data($("form[name='editform']")));
		});

		$("#<?php echo $this -> widgetId; ?> .delete").click(function(){
			var id = $("input[name='id']").val();
		    widget_confirm("確定刪除嗎？","warning","", function(){
		    	widget_load('<?php echo $this -> widgetId; ?>','classDelete',{"id":id});
			});
		});

		$("#<?php echo $this -> widgetId; ?> .back").click(function(){
		    widget_load('<?php echo $this -> widgetId; ?>','main');
		});
	
	});			
</script>