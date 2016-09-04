<?php

		global $post;
		$url = get_permalink($post->ID);
    	if ((  (WC()->session->get('building_basket') == "true") && (WC()->session->get('show_reminder') !== "no"))  && (
		 	(strpos($url, "/build" ) == false) &&
		 	(strpos($url, "/checkout" ) == false) &&
		 	(strpos($url, "/cart" ) == false) &&
		 	(strpos($url, "/product" ) == false)
		 	)) {
		 	include('composite-data.php');
			if(	$current_quantity < $minimum_items) {
		 	?><div id="message-box"><a href="/build/choose-your-products"><p></p></a><div id="close-message" data-box="building-reminder"><i class="fa fa-times-circle-o" aria-hidden="true"></i></div>
			<script>
        jQuery('#message-box p').html("<?php echo get_field('notification_basket_in_progress','option'); ?>");
        jQuery('#message-box').fadeIn('fast');
        jQuery(document).on('click', '#close-message', function(event) {
	    jQuery('#message-box').fadeOut('fast');
	            jQuery.post('/wp-admin/admin-ajax.php', {'action':'clear_reminder'}, function(data) {
            });
    });
		</script>
		<?php }
	}
	 ?>