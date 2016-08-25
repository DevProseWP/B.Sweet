<?php
/**
 * Single product short description
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/short-description.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post; 
global $building_basket;
if ($building_basket) { ?>
<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri(); ?>/quota.js'></script>
<script>
var	products_volume = <?php $vol =  get_field('product_volume', $post->ID); echo ($vol) ? $vol : 1 ; ?>;
var	products_size  = <?php $size =   get_field('product_size', $post->ID); echo ($size) ? $size : 1 ?>;	

</script>
<?php }


if ( ! $post->post_excerpt ) {
	return;
}

?>


<div itemprop="description">

	<?php echo apply_filters( 'woocommerce_short_description', $post->post_excerpt ) ?>
</div>
