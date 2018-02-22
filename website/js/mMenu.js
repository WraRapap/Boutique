jQuery(document).ready(function($){
	var $lateral_menu_trigger = $('#menu-trigger'),		
		$content_wrapper = $('.main-content'),
		$navigation = $('header');

	//open-close lateral menu clicking on the menu icon
	$lateral_menu_trigger.on('click', function(event){
		event.preventDefault();
		
		$lateral_menu_trigger.toggleClass('is-clicked');
		$navigation.toggleClass('menu-is-open');
		$content_wrapper.toggleClass('menu-is-open').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			// firefox transitions break when parent overflow is changed, so we need to wait for the end of the trasition to give the body an overflow hidden
			$('body').toggleClass('overflow-hidden');
		});
		$('#mobile-nav').toggleClass('menu-is-open');
		
		//check if transitions are not supported - i.e. in IE9
		if($('html').hasClass('no-csstransitions')) {
			$('body').toggleClass('overflow-hidden');
		}
	});

	//close lateral menu clicking outside the menu itself
	$content_wrapper.on('click', function(event){
		if( !$(event.target).is('#menu-trigger, #menu-trigger span') ) {
			$lateral_menu_trigger.removeClass('is-clicked');
			$navigation.removeClass('menu-is-open');
			$content_wrapper.removeClass('menu-is-open').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
				$('body').removeClass('overflow-hidden');
			});
			$('#mobile-nav').removeClass('menu-is-open');
			//check if transitions are not supported
			if($('html').hasClass('no-csstransitions')) {
				$('body').removeClass('overflow-hidden');
			}

		}
	});
	

	//open (or close) submenu items in the lateral menu. Close all the other open submenu items.
	$('.item-has-children').children('a').on('click', function(event){
		event.preventDefault();
		$(this).toggleClass('submenu-open').next('.sub-menu').slideToggle(200).end().parent('.item-has-children').siblings('.item-has-children').children('a').removeClass('submenu-open').next('.sub-menu').slideUp(200);
	});
	
	
	
	$('.has-arrow').children('a').on('click', function(event){
		event.preventDefault();
		$(this).toggleClass('submenu-open').next('.filter-submenu').slideToggle(500).end().parent('.has-arrow').siblings('.has-arrow').children('a').removeClass('submenu-open').next('.filter-submenu').slideUp(500);
	});
	
});

jQuery(document).ready(function($){
	var $lateral_menu_trigger = $('#filter-toggle'),		
		$content_wrapper = $('.main-content'),
		$navigation = $('.mobile-filter-wrap');

	//open-close lateral menu clicking on the menu icon
	$lateral_menu_trigger.on('click', function(event){
		event.preventDefault();
		
		$lateral_menu_trigger.toggleClass('is-clicked');
		$navigation.toggleClass('menu-is-open2');
		$content_wrapper.toggleClass('menu-is-open2').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
			// firefox transitions break when parent overflow is changed, so we need to wait for the end of the trasition to give the body an overflow hidden
			$('body').toggleClass('overflow-hidden');
		});
		$('#mobile-filter').toggleClass('menu-is-open2');
		
		//check if transitions are not supported - i.e. in IE9
		if($('html').hasClass('no-csstransitions')) {
			$('body').toggleClass('overflow-hidden');
		}


	});


	//close lateral menu clicking outside the menu itself
	$content_wrapper.on('click', function(event){
		if( !$(event.target).is('#filter-toggle') ) {
			$lateral_menu_trigger.removeClass('is-clicked');
			$navigation.removeClass('menu-is-open');
			$content_wrapper.removeClass('menu-is-open2').one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function(){
				$('body').removeClass('overflow-hidden');
			});
			$('#mobile-filter').removeClass('menu-is-open2');
			//check if transitions are not supported
			if($('html').hasClass('no-csstransitions')) {
				$('body').removeClass('overflow-hidden');
			}

		}
	});
	

	
});
