<?php


add_action('woocommerce_add_to_cart', 'catchBasketName');
function catchBasketName($woo){
if (isset($_POST['name_your_basket'])) 
{

		WC()->session->set('named_basket',$_POST['name_your_basket']);

}
return $woo;
}



/** custom shortcode for products page */

function woocommerce_template_loop_product_thumbnail() {

	  global $product;
      $attachment_ids = $product->get_gallery_attachment_ids();
      if(sizeof($attachment_ids) > 0) {
      	$output = "<div class='flip-container'><div class='lower-image'>";

      	$html = wp_get_attachment_image($attachment_ids[0], "shop_catalog", false);

		$output .= apply_filters( 'post_thumbnail_html',$html,$product->ID, $attachment_ids[0], "shop_catalog");

      	$output .= "</div><div class='upper-image'>" . woocommerce_get_product_thumbnail() . "</div></div>";
      	echo $output;
      } else {
		echo woocommerce_get_product_thumbnail();
	  }

}

function productGroup($atts) {
	
	$a = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'desc',
			'include_children' => FALSE,
			'echo'	   => FALSE,
			'category' => '',  // Slugs
			'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
			'maxsize' => 1
    ), $atts );

/* build filter selector */
	$cur_term = get_term_by( 'slug', $a['category'], 'product_cat');
	$children = (array) get_term_children( $cur_term->term_id, 'product_cat');
	if($children){
	$child_selector = '
	<div class="child-selector">
		<select name="subcat" class="subcat-selector" data-target="'.$cur_term->term_id.'">
			<option selected disabled>Category Filter</option>
			
		';
	foreach ($children as $term) {
		$subcat = get_term($term, 'product_cat');
		$child_selector .= "<option value='".$subcat->term_id."'>".$subcat->name."</option>" . PHP_EOL;
	}
	$child_selector .= '
	<option value="-1">Clear Filters</options>
	</select
>	</div>
	';
	}
	$ordering_args = WC()->query->get_catalog_ordering_args( $a['orderby'], $a['order'] );
	$meta_query    = WC()->query->get_meta_query();
    $query_args    = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'orderby'             => $ordering_args['orderby'],
		'order'               => $ordering_args['order'],
		'posts_per_page'      => $a['per_page'],
		'meta_query'          => $meta_query
	);

	$query_args = maybe_add_category_args( $query_args, $a['category'], $a['operator'], $a['include_children'] );
	if ( isset( $ordering_args['meta_key'] ) ) {
		$query_args['meta_key'] = $ordering_args['meta_key'];
	}
	global $woocommerce_loop;
	
	$products      = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $query_args, $a,'product_cat' ) );
	$columns       = absint( $a['columns'] );
	$woocommerce_loop['columns'] = $columns;
	$woocommerce_loop['product_group'] = TRUE;
	$woocommerce_loop['echo'] = $a['echo'];
	$woocommerce_loop['maxsize'] = $a['maxsize'];
	$woocommerce_loop['counter'] = 0;
	$woocommerce_loop['displayed'] = 0;

	ob_start();
	if ( $products->have_posts() ) : ?>
		<?php do_action( "woocommerce_shortcode_before_product_cat_loop" ); ?>
		<?php woocommerce_product_loop_start(); ?>
			<?php while ( $products->have_posts() ) : $products->the_post(); ?>
				<?php wc_get_template_part( 'content', 'product' ); ?>
			<?php endwhile; // end of the loop. ?>
		<?php woocommerce_product_loop_end(); ?>
		<?php do_action( "woocommerce_shortcode_after_product_cat_loop" ); ?>
	<?php endif;

	woocommerce_reset_loop();
	wp_reset_postdata();
	WC()->query->remove_ordering_args();
	if(!$a['echo']) {
		return '<div class="woocommerce ajax-container group-'.$cur_term->term_id.' columns-' . $columns . '">' . $child_selector . '<div class="working">
	
		<div class="cssload-loader">
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
			<div class="cssload-side"></div>
		</div></div><div class="product_group" id="group-'.$cur_term->term_id.'">' . ob_get_clean() . '</div></div>';

		
	} else {

		if($woocommerce_loop['displayed'] < 1) {
			return'<h5>Nothing found</h5>' . $loop['loop'];
		} else {
	
		 return ob_get_clean();
		}
	}
}

add_shortcode( 'product_group', 'productGroup' );

function maybe_add_category_args( $args, $category, $operator, $include_children ) {
		if ( ! empty( $category ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'terms'    => array_map( 'sanitize_title', explode( ',', $category ) ),
					'field'    => 'slug',
					'include_children' => $include_children,
					'operator' => $operator
				)
			);
		}

		return $args;
	}


add_action( 'wp_ajax_remove_product', 'remove_product' );
add_action( 'wp_ajax_nopriv_remove_product', 'remove_product' );
function remove_product(){
	$key = $_REQUEST['key'];
	WC()->cart->remove_cart_item($key);
	
	die();
}

add_action( 'wp_ajax_update_sub_cat', 'update_sub_cat' );
add_action( 'wp_ajax_nopriv_update_sub_cat', 'update_sub_cat' );

function update_sub_cat() {
	$subcat = $_REQUEST['showcat'];
	$target = $_REQUEST['target'];
	$maxsize = $_REQUEST['max-size'];
	if ($subcat == -1){
		$term = get_term($target, 'product_cat');
		$replace = productGroup([
			'category' => $term->slug,
			'columns' => 3,
			'order' => 'asc',
			'per_page' => 50,
			'echo' => true,
			'maxsize' => $maxsize
		]);

	} else {
		$term = get_term($subcat, 'product_cat');
		$replace = productGroup([
			'category' => $term->slug,
			'columns' => 3,
			'order' => 'asc',
			'per_page' => 50,
			'echo' => true,
			'maxsize' => $maxsize
		]);
	}

	echo $replace;
	die();
}

/**
 *  ACF Options Page
 */
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page(array(
		'page_title' 	=> 'Bsweet General Settings',
		'menu_title'	=> 'Bsweet Settings',
		'menu_slug' 	=> 'bsweet-general-settings',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_sub_page(array(
		'page_title' 	=> 'Bsweet Notification Settings',
		'menu_title'	=> 'Notifications',
		'parent_slug'	=> 'bsweet-general-settings',
	));
	

	
}



/**
 * Head Filter to insert notification variables
 */

add_action('wp_head','defineVariables');
function defineVariables(){
	$output = "\n<script>\n";
	$output .= "var notification_add_products ='".get_field('notification_add_products','option')."';\n";
	$output .= "var notification_basket_full ='".get_field('notification_basket_full','option')."';\n";
	$output .= "var notification_progress_text ='".get_field('notification_progress_text','option')."';\n";
	$output .= "var notification_choose_between ='".get_field('notification_choose_between','option')."';\n";
	$output .= "var notification_no_room ='".get_field('notification_no_room','option')."';\n";
	$output .= "var notification_too_big ='".get_field('notification_too_big','option')."';\n";
	$output .= "var notifications_not_enough ='".get_field('notifications_not_enough','option')."';\n";
	$output .= "var notification_progress_checkout ='".get_field('notification_progress_checkout','option')."';\n";
	$output .= "</script>\n";
	echo $output;
}

function wc_cart_item_name_hyperlink( $link_text, $cart_item ) {
	if(isset($cart_item['composite_data'])) {
		$title = get_the_title($cart_item['composite_data'][key($cart_item['composite_data'])]['product_id']);

		return $title;
	} else {
    return  $cart_item['data']->get_title();
	}
}
/* Filter to override cart_item_name */
add_filter( 'woocommerce_cart_item_name', 'wc_cart_item_name_hyperlink', 15, 2 );


function wc_get_basket_thumb_maybe($product_get_image,  $cart_item) {
		if(isset($cart_item['composite_data'])) {
			$_temp = new WC_Product($cart_item['composite_data'][key($cart_item['composite_data'])]['product_id']);
			remove_filter( 'woocommerce_cart_item_thumbnail', 'wc_get_basket_thumb_maybe');
 			$product_get_image = apply_filters( 'woocommerce_cart_item_thumbnail', $_temp->get_image(), $cart_item, $cart_item_key );
 			add_filter('woocommerce_cart_item_thumbnail', 'wc_get_basket_thumb_maybe', 10, 2);

	 }
		return $product_get_image; 
		

}

add_filter('woocommerce_cart_item_thumbnail', 'wc_get_basket_thumb_maybe', 10, 2);
?>
