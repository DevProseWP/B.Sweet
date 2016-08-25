<?php get_header(); ?>

						<div class="featured-image-fallback">
							<div id="featured-image">
							</div>
						</div>

			<header class="article-header">

				<h1 class="page-title" itemprop="headline">Page not found</h1>

				<!-- <p>These are not the baskets you are looking for.</p> -->

			</header> <?php // end article header ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<main id="main" class="m-all t-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

							<article>

								<section class="page-not-found" itemprop="articleBody">
									<p>
										The content or page you are looking for does not exist or could not be found, <br>please navigate back or go to the home page. <br><br>
										<a href="<?php echo home_url(); ?>">Home</a>
									</p>
								</section>

							</article>

						</main>

				</div>

			</div>

<?php get_footer(); ?>
