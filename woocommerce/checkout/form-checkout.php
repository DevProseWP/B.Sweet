<!-- <code class="const-banner">form-checkout.php</code> -->
<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $current_quantity;
global $maximum_items;	
global $minimum_items;
global $custom_basket;

if ((($current_quantity <= $maximum_items)&&($current_quantity >= $minimum_items))||(!$custom_basket)){

wc_print_notices();

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout
if ( ! $checkout->enable_signup && ! $checkout->enable_guest_checkout && ! is_user_logged_in() ) {
	echo apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) );
	return;
}

?>
<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

	<?php if ( sizeof( $checkout->checkout_fields ) > 0 ) : ?>

		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

		<h2 style="text-align:center;">If you are a new customer then start here!</h2>

		<div class="col" id="customer_details">
			<div class="col">
				<?php do_action( 'woocommerce_checkout_billing' ); ?>
			</div>

			<div class="col">
				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
			</div>
		</div>

		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

	<?php endif; ?>

	<h3 id="order_review_heading"><?php _e( 'Your order', 'woocommerce' ); ?></h3>

	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

	<div id="order_review" class="woocommerce-checkout-review-order">
		<?php do_action( 'woocommerce_checkout_order_review' ); ?>
	</div>

	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); 

} 
?>
<style>
	 .fl-module-button {display: none;}

</style>
<div style="text-align: center;">

<?php
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
</div>

