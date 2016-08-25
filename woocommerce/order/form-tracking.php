<!-- <code class="const-banner">form-tracking.php</code> -->
<div class="tracking-form-styling">
<?php
/**
 * Order tracking form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/form-tracking.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

?>

<form action="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" method="post" class="track_order">

<i class="fa fa-truck fa-5x" style="display: block; text-align: center; padding: 0 0 20px;"></i>

<p class="track-order-text">
	Please fill out the form below to check the status of your order, for security reasons we require both your <b>Order Number</b> and your <b>Email</b>.
	<br><br>
	Your Order number can be found in your confirmation email we sent to you after your purchase.
</p>

	<p class="tracking-input"><label for="orderid"><?php _e( 'Order ID', 'woocommerce' ); ?><i style="color: #e74c3c;">*</i></label> <input class="input-text" type="text" name="orderid" id="orderid" placeholder="<?php esc_attr_e( 'Found in your order confirmation email.', 'woocommerce' ); ?>" /></p>

	<p class="tracking-input"><label for="order_email"><?php _e( 'Billing Email', 'woocommerce' ); ?><i style="color: #e74c3c;">*</i></label> <input class="input-text" type="text" name="order_email" id="order_email" placeholder="<?php esc_attr_e( 'Email you used during checkout.', 'woocommerce' ); ?>" /></p>
	<div class="clear"></div>

	<p class="form-row"><input type="submit" class="button" style="color: #fff;" name="track" value="<?php esc_attr_e( 'Track', 'woocommerce' ); ?>" /></p>
	<?php wp_nonce_field( 'woocommerce-order_tracking' ); ?>

	<div class="order-form-account">
		<b>NOTE</b> If you have an account with us you can check your current orders <a href="/my-account">here</a>
	</div>

</form>
</div>
