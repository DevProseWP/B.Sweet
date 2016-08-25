<!-- <code class="const-banner">woocomerce.php</code> -->
<?php  
if (WC()->session->get('building_basket') == "true") {
	include('composite-data.php');
} else {
	$building_basket = false;
	$custom_basket = false;
}
?>


<?php get_header('compact'); ?>
<div id="message-box">
<p></p>
	<div id="close-message"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
</div>
</div>
<div class="woocommerce_wrap">
<div class="woo-main-wrap">


			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<a href="#" class="back-link btn-wide-w shop-back"><i class="fa fa-arrow-left"></i>BACK</a>
								<!-- <button type="button" onclick="location.href = document.referrer;">Go Back referrer</button> -->
								<section class="entry-content cf content-container" itemprop="articleBody">
									<?php woocommerce_content(); ?>
								</section>
								<a href="#" class="back-link btn-wide-w shop-back"><i class="fa fa-arrow-left"></i>BACK</a>

							</article>

						</main>

				</div>

			</div>
		</div>

<?php
if (($building_basket)||($basket_id)) { ?>

<script>
	var curr_volume = <?php echo $current_volume; ?>;
	var curr_quantity = <?php echo $current_quantity; ?>;
	var max_volume = <?php echo $maximum_volume; ?>;
	var max_size = <?php echo $maximum_size; ?>;
	var maximum_items = <?php echo $maximum_items; ?>;
	var minimum_items = <?php echo $minimum_items; ?>;
	
</script>
<?php }

get_footer('bare'); ?>
</div>

