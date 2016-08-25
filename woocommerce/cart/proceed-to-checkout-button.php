<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $current_quantity;
global $maximum_items;	
global $minimum_items;
global $custom_basket;

if ((($current_quantity <= $maximum_items)&&($current_quantity >= $minimum_items))||(!$custom_basket)){

?>

<a href="<?php echo esc_url( wc_get_checkout_url() ) ;?>" class="checkout-button button alt wc-forward">
	<?php echo __( 'Proceed to Checkout', 'woocommerce' ); ?>
</a>
<?php } 

if (($current_quantity < $minimum_items)&&($custom_basket)){
?>
<h3>Not Enough Products. You Need a Minimum of <?php echo $minimum_items;?> Products in Your Basket.</h3>
<a href="/build/choose-your-products" class="button alt wc-forward">Return To Products</a>
<?php
}
if (($current_quantity > $maximum_items)&&($custom_basket)){
?>
<h3>Too Many Products in Your Basket.  You Can Have a Maximum of <?php echo $maximum_items; ?> Products in Your Basket.</h3>
<a href="/build/choose-your-products" class="button alt wc-forward">Return To Products</a>
<?php
}
?>
