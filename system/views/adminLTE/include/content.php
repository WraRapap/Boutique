
    <?php foreach($this -> widgets as $widget): ?>
      <div id="<?php echo $widget -> widgetID; ?>" class="col-xs-<?php echo $widget -> phone; ?> col-sm-<?php echo $widget -> pad; ?> col-md-<?php echo $widget -> desktop; ?>" <?php if($widget -> hide): ?>style="display:none;"<?php endif; ?>></div>
    <?php endforeach; ?>
    
    <script>
    $("document").ready(function(){
      <?php foreach($this -> widgets as $widget): ?>
      widget_init('<?php echo $widget -> widgetID; ?>','','<?php echo $_SERVER['QUERY_STRING']; ?>');
      <?php endforeach; ?>
    });
    </script>


    