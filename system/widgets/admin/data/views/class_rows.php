<div class="box">
  <div class="box-header">
    <div class="box-tools pull-left" style="position: static;">

      <?php if(in_array("ADD",$this -> widgetMeta -> class_control)): ?>      
      <a class="add_class label label-success fa fa-folder" title="新增分類" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      

      <?php if(count($this -> data) == 0 && $this -> widgetMeta -> dynamic_level == "Y" && in_array("ADD",$this -> widgetMeta -> item_control)): ?>      
      <a class="add_item label label-success fa fa-plus" title="新增項目" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      <?php if(in_array("DEL",$this -> widgetMeta -> class_control)): ?>  
      <a class="batch_delete label label-danger fa fa-trash" title="批次刪除分類" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      <?php if(in_array("PUBLISH",$this -> widgetMeta -> class_control)): ?>
      <a class="batch_publish label label-success fa fa-cloud-upload" title="批次上架" type="button" style="font-size:16px;padding:10px;"> </a>
      <a class="batch_unpublish label bg-purple fa fa-cloud-download" title="批次下架" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      <?php if(in_array("SORT",$this -> widgetMeta -> class_control)): ?>
      <a class="sort label label-info fa fa-sort-amount-desc" title="排序" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      <?php if(in_array("EXPORT",$this -> widgetMeta -> class_control)): ?>
      <a class="export label bg-maroon fa fa-download" title="匯出" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      <?php if($this -> tool_io -> get("id") != ""): ?>
      <a class="return label bg-orange fa  fa-mail-reply" title="回上一層" type="button" style="font-size:16px;padding:10px;"> </a>
      <?php endif; ?>

      
    </div>


    <div class="box-tools pull-right" style="position: static;">
      <ol class="breadcrumb">
        <li><i class="fa fa-tags"></i> <?php echo $this -> widgetMeta -> title; ?></li>
        <?php foreach($this -> navigation as $navigation): ?>
        <li>
          <a href="javascript:void(0);" class="navigation" id="<?php echo $navigation -> id; ?>"> <?php echo $navigation -> title; ?></a>
        </li>
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
        <?php if($field -> list != "Y"){continue;} ?>
        <th><?php echo $field -> name; ?></th>
        <?php endforeach; ?>

        <th>操作</th>
      </tr>
      </thead>
      <tbody class="items">
      <?php foreach($this -> data as $data): ?>
      <tr>
        <td>
          <input type="checkbox" name="ids[]" value="<?php echo $data -> id; ?>"/>
        </td>
         
        <?php foreach($field_metas as $variable => $field): ?>
        <?php if($field -> list != "Y"){continue;} ?>
        <td><?php echo $data -> {$field -> variable} -> getText(); ?></td>
        <?php endforeach; ?>

        
        <td>

          <a href="javascript:void(0);" class="browser label bg-olive fa fa-folder-open" title="瀏覽" rel="<?php echo $data -> id; ?>"> <span></span></a>

          <?php if(in_array("FIX",$this -> widgetMeta -> class_control)): ?>
          <a href="javascript:void(0);" class="edit label label-primary fa fa-pencil" title="編輯" rel="<?php echo $data -> id; ?>"> <span></span></a>
          <?php endif; ?>
          
          <!--
          <a href="javascript:void(0);" class="clone label bg-purple fa fa-clone" title="複製" rel="<?php echo $data -> id; ?>"> <span></span></a>
          -->

          <?php if(in_array("DEL",$this -> widgetMeta -> class_control)): ?>
          <a href="javascript:void(0);" class="delete label label-danger fa fa-trash" title="刪除" rel="<?php echo $data -> id; ?>"> <span></span></a>
          <?php endif; ?>
        
          
        </td>
			</tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div><!-- /.box-body -->


  <?php
  $page_info = $this -> tool_pagination -> get_page_list(); 
  $next_page = $this -> tool_pagination -> get_next_page();
  $prev_page = $this -> tool_pagination -> get_previous_page();
  ?>
  <div class="box-footer clearfix">
    <ul class="pagination pagination-sm no-margin pull-right">
      <?php if($prev_page -> has_previous): ?>
      <li><a class="page" href="javascript:void(0);" data-href="<?php echo $prev_page -> url;?>">«</a></li>
      <?php endif; ?>

      <?php foreach($page_info as $key => $page): ?>
      <?php if($page->current): ?>
      <li class="active"><a href="javascript:void(0);"><?php echo $key; ?></a></li>
      <?php else: ?>
      <li><a class="page" href="javascript:void(0);" data-href="<?php echo $page -> url; ?>"><?php echo $key; ?></a></li>
      <?php endif; ?>
      <?php endforeach; ?>

      <?php if($next_page -> has_next): ?>
      <li><a class="page" href="javascript:void(0);" data-href="<?php echo $next_page -> url;?>">»</a></li>
      <?php endif; ?>
    </ul>
  </div>
</div><!-- /.box -->



<script>
  $("document").ready(function(){

    $("#<?php echo $this -> widgetId; ?> .navigation").click(function(){
      var id = $(this).attr("id");
      widget_load('<?php echo $this -> widgetId; ?>','classRows',"id=" + id);
    });
    

    // 瀏覽分類
    $("#<?php echo $this -> widgetId; ?> .browser").click(function(){
      var id = $(this).attr("rel");
      <?php if($this -> widgetMeta -> class_level == 1): ?>
      widget_load('<?php echo $this -> widgetId; ?>','rows',"id="+id);
      <?php else: ?>
      widget_load('<?php echo $this -> widgetId; ?>','classRows',"id="+id);
      <?php endif; ?>
    });

    // 新增分類
    $("#<?php echo $this -> widgetId; ?> .add_class").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','classEdit', {"parentID": '<?php echo $this -> tool_io -> get("id"); ?>'});
    });

    // 新增項目
    $("#<?php echo $this -> widgetId; ?> .add_item").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','edit', {"parentID": '<?php echo $this -> tool_io -> get("id"); ?>'});
    });

    // 編輯
    $("#<?php echo $this -> widgetId; ?> .edit").click(function(){
      var id = $(this).attr("rel");
      widget_load('<?php echo $this -> widgetId; ?>','classEdit',"id=" + id);
    });

    // 單一刪除
    $("#<?php echo $this -> widgetId; ?> .delete").click(function(){
      var id = $(this).attr("rel");
      widget_confirm("確定刪除分類嗎？","warning","", function(){
        widget_load('<?php echo $this -> widgetId; ?>','classDelete',{"id":id});
      });
    });

    // 批次刪除
    $("#<?php echo $this -> widgetId; ?> .batch_delete").click(function(){
      widget_confirm("確定刪除您所選擇的分類嗎？","warning","", function(){
        widget_load(
          '<?php echo $this -> widgetId; ?>',
          'classBatchDelete',
          widget_get_form_data($("#<?php echo $this -> widgetId; ?> input[name='ids[]']"))
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
      widget_load('<?php echo $this -> widgetId; ?>','classSort',"id=<?php echo $this -> tool_io -> get("id"); ?>");
    });

    // 回上一層
    $("#<?php echo $this -> widgetId; ?> .return").click(function(){
      widget_load('<?php echo $this -> widgetId; ?>','classBack',"id=<?php echo $this -> tool_io -> get("id"); ?>");
    });

    // 分頁
    $("#<?php echo $this -> widgetId; ?> .page").click(function(){

      var parameter = "";
      var href = $(this).attr("data-href");
      var params = href.replace("?","").split("&");
      for(var i=0;i<params.length;i++){
        var pair = params[i].split("=");
        if(pair[0] == "action" || pair[0] == "widget") continue;
        parameter += "&" + params[i];
      }
      if(parameter != ""){
        parameter = parameter.substr(1);
      }

      // console.log(params);

      widget_load('<?php echo $this -> widgetId; ?>','classRows',parameter);
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