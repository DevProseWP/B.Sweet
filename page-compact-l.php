<?php
/*
 Template Name: Compact Page Wide No BG
*/

if (WC()->session->get('building_basket') == "true") {
	header("Cache-Control: no-store, must-revalidate, max-age=0");
	header("Pragma: no-cache");
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	include('composite-data.php');
} else {
	$building_basket = false;
	$custom_basket = false;
	if(basename(get_permalink())=='choose-your-products'){
		wp_redirect('/build/custom-basket/');
		exit;
	}
}

?>
<?php get_header('compact'); ?>

<script type="text/javascript">
var	product_volume = <?php echo (get_field('product_volume', $basket_id)) ? get_field('product_volume', $basket_id) : 1; ?>;
var	product_size  = <?php echo (get_field('product_size', $basket_id)) ? get_field('product_size', $basket_id) : 1; ?>;
var	minimum_items 	 = <?php echo $minimum_items; ?>;
var	maximum_items = <?php echo $maximum_items; ?>;
</script>
<div id="message-box">
<p></p>
	<div id="close-message"><i class="fa fa-times-circle-o" aria-hidden="true"></i>
</div>
</div>
			<div id="content">

				<div id="inner-content" class="wrap  lrg-wrap cf">

						<main id="main" class="m-all t-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										// the content (pretty self explanatory huh)
										the_content();

										/*
										 * Link Pages is used in case you have posts that are set to break into
										 * multiple pages. You can remove this if you don't plan on doing that.
										 *
										 * Also, breaking content up into multiple pages is a horrible experience,
										 * so don't do it. While there are SOME edge cases where this is useful, it's
										 * mostly used for people to get more ad views. It's up to you but if you want
										 * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
										 *
										 * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
										 *
										*/
										wp_link_pages( array(
											'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
											'after'       => '</div>',
											'link_before' => '<span>',
											'link_after'  => '</span>',
										) );
									?>
								</section> <?php // end article section ?>

							</article>

							<?php endwhile; endif; ?>

						</main>

				</div>

			</div>
			<?php

if ($building_basket) { ?>

<script>
	var curr_volume = <?php echo $current_volume; ?>;
	var curr_quantity = <?php echo $current_quantity; ?>;
	var max_volume = <?php echo $maximum_volume; ?>;
	var max_size = <?php echo $maximum_size; ?>;
	var maximum_items = <?php echo $maximum_items; ?>;
	var minimum_items = <?php echo $minimum_items; ?>;
	jQuery('.subcat-selector').prop("selectedIndex",0);
</script>
<?php } ?>
<script type='text/javascript' src='<?php echo get_stylesheet_directory_uri(); ?>/quota.js'></script>

<?php get_footer('compact'); ?>
