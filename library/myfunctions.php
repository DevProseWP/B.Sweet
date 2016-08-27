<?php

/** custom shortcode for products page */

function productGroup($atts) {
	
	$a = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'desc',
			'include_children' => FALSE,
			'echo'	   => FALSE,
			'category' => '',  // Slugs
			'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
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
	</select>
	</div>
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
		return '<div class="woocommerce columns-' . $columns . '">' . $child_selector . '<div class="product_group" id="group-'.$cur_term->term_id.'">' . ob_get_clean() . '</div></div>';
	} else {
		return ob_get_clean();
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


add_action( 'wp_ajax_update_sub_cat', 'update_sub_cat' );
add_action( 'wp_ajax_nopriv_update_sub_cat', 'my_action_callback' );

function update_sub_cat() {
	$subcat = $_REQUEST['showcat'];
	$target = $_REQUEST['target'];
	if ($subcat == -1){
		$term = get_term($target, 'product_cat');
		$replace = productGroup([
			'category' => $term->slug,
			'columns' => 3,
			'order' => 'asc',
			'per_page' => 50,
			'echo' => true
		]);

	} else {
		$term = get_term($subcat, 'product_cat');
		$replace = productGroup([
			'category' => $term->slug,
			'columns' => 3,
			'order' => 'asc',
			'per_page' => 50,
			'echo' => true
		]);
	}

	echo $replace;
	die();
}


/**
 *
 *
 *class="first post-1032 type-product status-publish has-post-thumbnail product_cat-all-product-categories product_cat-beach-and-bath taxable shipping-taxable purchasable product-type-variable product-cat-all-product-categories product-cat-beach-and-bath has-default-attributes has-children instock"
 *
 * class="first post-801 product type-product status-publish has-post-thumbnail product_cat-all-product-categories product_cat-toys taxable shipping-taxable purchasable product-type-simple product-cat-all-product-categories product-cat-toys instock"
 */

?>
