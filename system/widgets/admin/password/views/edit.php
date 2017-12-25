
<!-- general form elements -->
<div class="box box-primary">
	<!-- form start -->
	<form name="editform_<?php echo $this -> widgetId; ?>" method="post" enctype="multipart/form-data" data-signed="<?php echo base64_encode(json_encode(array("data_source" => $this -> widgetMeta -> data_source))); ?>"> 
		<div class="box-header">
    	
    		<div class="box-tools pull-left" style="position: static;">
        		<div class="btn-group" style="margin-right:20px;">
            		<button class="save btn btn-success fa fa-save" title="修改" type="button" name="save"> 修改</button>
				</div>
			</div>
        	
        	<div class="box-tools pull-right" style="position: static;">
		      <ol class="breadcrumb">
		        <li><i class="fa fa-tags"></i> <?php echo $this -> widgetMeta -> title; ?></li>
		      </ol>
		    </div>
			
		
		</div><!-- /.box-header -->
        
    	<div class="box-body">
    		
            <div class="form-group">
            	<label for="oldPassword">舊密碼:</label>
            	<input type="password" name="oldPassword" value="" class="form-control">
            </div>

            <div class="form-group">
            	<label for="newPassword">新密碼:</label>
            	<input type="password" name="newPassword" value="" class="form-control">
            </div>

            <div class="form-group">
            	<label for="confirm">確認密碼:</label>
            	<input type="password" name="confirm" value="" class="form-control">
            </div>
		</div>

		<div class="box-footer">
			<div class="box-tools pull-left" style="position: static;">
            	<div class="btn-group" style="margin-right:20px;">
                	<button class="save btn btn-success fa fa-save" title="修改" type="button" name="save"> 修改</button>
				</div>
			</div>
		</div>
		
	</form>
</div><!-- /.box -->

          
<script>
	$("document").ready(function(){
		
		$("#<?php echo $this -> widgetId; ?> .save").click(function(){
			widget_load(
				'<?php echo $this -> widgetId; ?>',
				'save',
				widget_get_form_data($("form[name='editform_<?php echo $this -> widgetId; ?>']"))
			);
		});
	});			
</script>