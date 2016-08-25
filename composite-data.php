<?php
		
		$building_basket = true;
		$custom_basket = true;
		$basket_data = WC()->session->get('custom_basket');
		$basket_id = $basket_data['id'];
		$maximum_volume =  $basket_data['maximum_volume'];
		$maximum_size  =  $basket_data['maximum_size'];
		$basket_max_prod_size = $maximum_size;
		$maximum_items = $basket_data['maximum_items'];	
		$minimum_items = $basket_data['minimum_items'];
		$excludes = $basket_data['composites'];
		$current_volume = 0;
		$current_quantity = 0;
		$items = WC()->cart->get_cart();
		if($items) {

			    foreach($items as $item => $values) { 
			       	if (!in_array($values['data']->id, $excludes)) {     	
			       		$prodsize = (get_field('product_size', $values['data']->id)) ? get_field('product_size', $values['data']->id) : 1 ; 
			       		$current_quantity = $current_quantity + ($values['quantity'] * $prodsize);
			       	}
			    }  
			}