
<!-- general form elements -->
<div class="box box-primary">
	<!-- form start -->
	<form name="editform_<?php echo $this -> widgetId; ?>" method="post" enctype="multipart/form-data" data-signed="<?php echo base64_encode(json_encode(array("id" => $this -> data -> id , "data_source" => $this -> widgetMeta -> data_source))); ?>"> 
		<div class="box-header">
    	
    		<div class="box-tools pull-left" style="position: static;">
        		<div class="btn-group" style="margin-right:20px;">
            		<button class="save btn btn-success fa fa-save" title="儲存" type="button" name="save"> 儲存</button>
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
			</div>
		</div>
		<input type="hidden" name="id" value="<?php echo $this -> data -> id; ?>" />
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
			widget_load(
				'<?php echo $this -> widgetId; ?>',
				'save',
				widget_get_form_data($("form[name='editform_<?php echo $this -> widgetId; ?>']"))
			);
		});

		$("#<?php echo $this -> widgetId; ?> .delete").click(function(){
			var id = $("form[name='editform_<?php echo $this -> widgetId; ?>'] input[name='id']").val();
		    widget_confirm("確定刪除嗎？","warning","", function(){
		    	widget_load('<?php echo $this -> widgetId; ?>','delete',{"id":id});
			});
		});

		$("#<?php echo $this -> widgetId; ?> .back").click(function(){
		    widget_load('<?php echo $this -> widgetId; ?>','main');
		});
	
	});			
</script>