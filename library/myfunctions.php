<?php

/** custom shortcode for products page */

function productGroup($atts) {
	
	$a = shortcode_atts( array(
			'per_page' => '12',
			'columns'  => '4',
			'orderby'  => 'title',
			'order'    => 'desc',
			'include_children' => FALSE,
			'category' => '',  // Slugs
			'operator' => 'IN' // Possible values are 'IN', 'NOT IN', 'AND'.
    ), $atts );

    $query_args    = array(
		'post_type'           => 'product',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
		'orderby'             => $ordering_args['orderby'],
		'order'               => $ordering_args['order'],
		'posts_per_page'      => $a['per_page'],
		'meta_query'          => $meta_query
	);

	$meta_query    = WC()->query->get_meta_query();
	$query_args = maybe_add_category_args( $query_args, $a['category'], $a['operator'], $a['include_children'] );
	if ( isset( $ordering_args['meta_key'] ) ) {
		$query_args['meta_key'] = $ordering_args['meta_key'];
	}
	global $woocommerce_loop;
	print_r($query_args);
	$products      = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $query_args, $a,'product_cat' ) );
	$columns       = absint( $a['columns'] );

	$woocommerce_loop['columns'] = $columns;
	$woocommerce_loop['product_group'] = TRUE;
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
	return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
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

?>
