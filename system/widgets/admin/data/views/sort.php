<div class="box">
  <div class="box-header">
    <div class="box-tools pull-left" style="position: static;">

      <button class="save btn btn-success fa fa-save" title="儲存" type="button" name="save"> 儲存</button>

      <button class="cancel btn bg-orange fa fa-times" title="取消" type="button" name="cancel"> 取消</button>
      
    </div>


    <div class="box-tools pull-right" style="position: static;">
      <ol class="breadcrumb">
        <li><i class="fa fa-tags"></i> <?php echo $this -> widgetMeta -> title; ?></li>
        <?php foreach($this -> navigation as $navigation): ?>
        <li><a href="javascript:void(0);" class="navigation" id="<?php echo $navigation -> id; ?>"> <?php echo $navigation -> title; ?></a></li>
        <?php endforeach; ?>
      </ol>
    </div>

    
    <div class="box-tools pull-right" style="position: static;">
	                
    </div>

	</div><!-- /.box-header -->
  
  <div class="box-body table-responsive no-padding">
    <table class="table table-hover">
      <thead>
		  <tr>
        <th>
           
        </th>
        <?php $field_metas = $this -> metas;?>

        <?php foreach($field_metas as $variable => $field): ?>
        <?php if($field -> list != "Y"){continue;} ?>
        <th><?php echo $field -> name?></th>
        <?php endforeach; ?>


      </tr>
      </thead>
      <tbody class="items">
      <?php foreach($this -> data as $data): ?>
      <tr>
        <td style="width:30px;cursor:pointer;" valign="middle" class="dropable noselect">
          <div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
          <div class="dropable fa fa-ellipsis-v" style="vertical-align: middle;"></div>
          <input type="hidden" name="ids[]" value="<?php echo $data -> id; ?>"/>
        </td>
         
        <?php foreach($field_metas as $variable => $field): ?>
        <?php if($field -> list != "Y"){continue;} ?>
        <td><?php echo $data -> {$field -> variable} -> getText(); ?></td>
        <?php endforeach; ?>

			</tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div><!-- /.box-body -->
</div><!-- /.box -->



<script>
  $("document").ready(function(){

    $("#<?php echo $this -> widgetId; ?> .navigation").click(function(){
      var id = $(this).attr("id");
      widget_load('<?php echo $this -> widgetId; ?>','classRows',"id=" + id);
    });

    // 儲存
    $("#<?php echo $this -> widgetId; ?> .save").click(function(){
       widget_load(
         '<?php echo $this -> widgetId; ?>',
         'sortSave',
         widget_get_form_data($("#<?php echo $this -> widgetId; ?> input[name='ids[]']"))
       );
    });

    // 取消
    $("#<?php echo $this -> widgetId; ?> .cancel").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','back',"id=<?php echo $this -> tool_io -> get("id"); ?>");
    });

    // 拖曳順序
      $(".items").sortable({
        handle : ".dropable"
      });

  });
</script>