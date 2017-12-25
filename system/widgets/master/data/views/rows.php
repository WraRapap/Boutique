<div class="box">
  <div class="box-header">
    <div class="box-tools pull-left" style="position: static;">

      <a class="add_item label label-success fa fa-file" title="新增項目" type="button" name="add_module" style="font-size:16px;padding:10px;"> </a>
      <a class="batch_delete label label-danger fa fa-trash" title="批次刪除" type="button" name="del_module" style="font-size:16px;padding:10px;"> </a>

       <?php if(in_array("SORT",$this -> widgetMeta -> item_control)): ?>
      <a class="sort label label-info fa fa-sort-amount-desc" title="排序" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>
      
      <?php if($this -> tool_io -> get("id") != ""): ?>
      <a class="return label bg-orange fa  fa-mail-reply" title="回上一層" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>
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
           <input type="checkbox" name="select_all" value="Y"/>
        </th>
        <?php $field_metas = $this -> metas; ?>

        <?php foreach($field_metas as $variable => $field): ?>
        <?php if($field["list"] != "Y"){continue;} ?>
        <th><?php echo $field["name"]?></th>
        <?php endforeach; ?>

        <th>操作</th>
      </tr>
      </thead>
      <tbody class="item">
      <?php foreach($this -> data as $data): ?>
      <tr>
        <td>
          <input type="checkbox" name="ids[]" value="<?php echo $data -> id; ?>"/>
        </td>
         
        <?php foreach($field_metas as $variable => $field): ?>
        <?php if($field["list"] != "Y"){continue;} ?>
        <td><?php echo $data -> {$variable} -> getText(); ?></td>
        <?php endforeach; ?>

        
        <td>
          <a href="javascript:void(0);" class="edit label label-primary fa fa-pencil" title="編輯" rel="<?php echo $data -> id; ?>"> <span></span></a>
          
          <!--
          <a href="javascript:void(0);" class="clone label bg-purple fa fa-clone" title="複製" rel="<?php echo $data -> id; ?>"> <span></span></a>
          -->
          <a href="javascript:void(0);" class="delete label label-danger fa fa-trash" title="刪除" rel="<?php echo $data -> id; ?>"> <span></span></a>
        
          
        </td>
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

    // 新增項目
    $("#<?php echo $this -> widgetId; ?> .add_item").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','edit',{"parentID": '<?php echo $this -> tool_io -> get("id"); ?>'});
    });

    // 編輯
    $("#<?php echo $this -> widgetId; ?> .edit").click(function(){
      var id = $(this).attr("rel");
      widget_load('<?php echo $this -> widgetId; ?>','edit',"id=" + id);
    });

    // 單一刪除
    $("#<?php echo $this -> widgetId; ?> .delete").click(function(){
      var id = $(this).attr("rel");
      widget_confirm("確定刪除項目嗎？","warning","", function(){
        widget_load('<?php echo $this -> widgetId; ?>','delete',"id=" + id);
      });
    });

     // 批次刪除
    $("#<?php echo $this -> widgetId; ?> .batch_delete").click(function(){
      
      widget_confirm("確定刪除您所選擇的項目嗎？","warning","", function(){
        widget_load(
          '<?php echo $this -> widgetId; ?>',
          'batchDelete',
          widget_get_form_data($("input[name='ids[]']"))
        );
      });
    });

    // 批次上架
    $("#<?php echo $this -> widgetId; ?> .batch_publish").click(function(){
       widget_load(
         '<?php echo $this -> widgetId; ?>',
         'classBatchPublish',
         widget_get_form_data($("#<?php echo $this -> widgetId; ?> input[name='ids[]']"))
       );
    });

    // 批次下架
    $("#<?php echo $this -> widgetId; ?> .batch_unpublish").click(function(){
       widget_load(
         '<?php echo $this -> widgetId; ?>',
         'classBatchUnPublish',
         widget_get_form_data($("#<?php echo $this -> widgetId; ?> input[name='ids[]']"))
       );
    });

     // 排序
    $("#<?php echo $this -> widgetId; ?> .sort").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','sort',"id=<?php echo $this -> tool_io -> get("id"); ?>");
    });

    // 回上一層
    $("#<?php echo $this -> widgetId; ?> .return").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','back',"id=<?php echo $this -> tool_io -> get("id"); ?>");
    });

    // 核取方塊效果
    $("#<?php echo $this -> widgetId; ?> input[type='checkbox']").iCheck({
      checkboxClass: 'icheckbox_flat-blue'
    });

    // 全選
    $("#<?php echo $this -> widgetId; ?> input[name='select_all']").on("ifChecked",function(){
      $("#<?php echo $this -> widgetId; ?> input[name='ids[]']").iCheck("check");
    }).on("ifUnchecked",function(){
      $("#<?php echo $this -> widgetId; ?> input[name='ids[]']").iCheck("uncheck");
    });
	});
</script>