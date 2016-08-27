


			<footer class="footer" role="contentinfo" itemscope itemtype="http://schema.org/WPFooter">

				<div id="inner-footer" class="cf">

					<nav role="navigation">
						<?php wp_nav_menu(array(
    					'container' => 'div',                           // enter '' to remove nav container (just make sure .footer-links in _base.scss isn't wrapping)
    					'container_class' => 'footer-nav cf',         // class of container (should you choose to use it)
    					'menu' => __( 'Footer Nav', 'bonestheme' ),   // nav name
    					'menu_class' => 'nav footer-nav cf',            // adding custom nav class
    					'theme_location' => 'footer-nav',             // where it's located in the theme
    					'before' => '',                                 // before the menu
    					'after' => '',                                  // after the menu
    					'link_before' => '',                            // before each link
    					'link_after' => '',                             // after each link
    					'depth' => 0,                                   // limit the depth of the nav
    					'fallback_cb' => 'bones_footer_links_fallback'  // fallback function
						)); ?>
					</nav>

					<p class="copyright">All rights reserved - <?php bloginfo( 'name' ); ?> <?php echo date('Y'); ?></p>
					<div class="social">
						<a href="https://www.facebook.com/bsweettoday/"><img src="/wp-content/themes/B.Sweet/library/images/social/facebook.png" alt="B.Sweet facebook" draggable="false" /></a>
						<a href="https://twitter.com/BSweetToday"><img src="/wp-content/themes/B.Sweet/library/images/social/twitter.png" alt="B.Sweet twitter" draggable="false" /></a>
						<a href="http://instagram.com/bsweet.today"><img src="/wp-content/themes/B.Sweet/library/images/social/instagram.png" alt="B.Sweet pinterest" draggable="false" /></a>
						<!-- <a href="#pinterest"><img src="/wp-content/themes/B.Sweet/library/images/social/pinterest.png" alt="B.Sweet twitter" draggable="false" /></a>
						<a href="#google"><img src="/wp-content/themes/B.Sweet/library/images/social/google.png" alt="B.Sweet google" draggable="false" /></a> -->
					</div>

				</div>

			</footer>

		</div>
<?php echo admin_url( 'admin-ajax.php' ); ?>
		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>


	</body>

</html> <!-- end of site. what a ride! -->
