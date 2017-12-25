    <ul class="sidebar-menu">
        
      <?php foreach($this -> menu as $menu): ?>
          <li class="treeview">
          
          <?php if(isset($menu -> items)): ?>

          <a href="#">
            <i class="<?php echo $menu -> icon; ?>"></i> <span><?php echo $menu -> title; ?></span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php foreach($menu -> items as $item): ?>
            <?php $active = ($item -> page == $this -> tool_io -> get("page_id"))?"class=\"active\"":""; ?>
            <li><a href="?page_id=<?php echo $item -> page; ?>" <?php echo $active; ?>><i class="<?php echo $item -> icon; ?>"></i> <?php echo $item -> title; ?></a></li>
            <?php endforeach; ?>
          </ul>


          <?php else: ?>
          
         

          <a href="?page_id=<?php echo $menu -> page; ?>">
            <i class="<?php echo $menu -> icon; ?> <?php // echo $active; ?>"></i> <span><?php echo $menu -> title; ?></span>
            <span class="pull-right-container">
            </span>
          </a>

          <?php endif; ?>
           </li>
        <?php endforeach; ?>
      </ul>

      <script type="text/javascript">
            $("document").ready(function() {
              $(".active").parents(".treeview").addClass("active");
            });
      </script>