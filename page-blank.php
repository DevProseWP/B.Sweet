<?php
/*
 Template Name: Blank Page
*/
?>
	<?php get_header(); ?>
	<style media="screen">
			header {display: none}
			footer {display: none}
			.fl-row-content-wrap,
			.fl-col-content,
			 .fl-node-content,
			 .fl-row, .fl-col  {
				 border-radius: 0;
			 }
	</style>
	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; endif; ?>
	<?php get_footer(); ?>
