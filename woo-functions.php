<?php

add_filter( 'woocommerce_add_cart_item', 'wcAfterAdd');
function wcAfterAdd($product){
	if (has_term( 73, 'product_cat',$product['product_id'])){
		WC()->session->set( 'building_basket' , "true" );
		$basket_data = [
			'id'	=> $product['product_id'],
			'maximum_volume'	=> (get_field('product_volume', $basket_id)) ?  get_field('product_volume', $basket_id) : 1,
			'maximum_size'	=> (get_field('product_size', $basket_id)) ? get_field('product_size', $basket_id) : 1 ,
			'maximum_items'	=> (get_field('maximum_items', $basket_id)) ? get_field('maximum_items', $basket_id) : 6 ,
			'minimum_items'	=> (get_field('minimum_items', $basket_id)) ? get_field('minimum_items', $basket_id) : 4,
			'composites'	=> [1198, $product['product_id']]
		];
		WC()->session->set( 'custom_basket' , $basket_data );
	}
	if (has_term( 35, 'product_cat',$product['product_id'])){ 
		$basket_data = WC()->session->get( 'custom_basket' );
		$basket_data['composites'][] = $product['product_id'];
		WC()->session->set( 'custom_basket' , $basket_data );
	}

	return $product;
}

add_action('woocommerce_cart_emptied', 'wcRemove');
add_action('woocommerce_thankyou', 'wcRemove');
function wcRemove(){
	WC()->session->set( 'building_basket' , "false" );
	WC()->session->set( 'custom_basket', "");
	WC()->session->set( 'name_custom_basket', "");
}