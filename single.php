<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package reddit
 */

get_header(); ?>
	<section id="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-8">
					<div id="primary" class="content-area">
						<main id="main" class="single-page">
							<div class="featured-img">
								<?php 
									if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
										the_post_thumbnail();
									} 
								?>
							</div>
						<?php while ( have_posts() ) : the_post(); ?>

							<?php get_template_part( 'template-parts/content', 'single' ); ?>

							
							

						<?php endwhile; // End of the loop. ?>
						<div class="share_block clearfix">
							<h4>Share This Post</h4>
							<a target="_blank" href="http://www.facebook.com/share.php?u=<?php echo get_permalink(); ?>"
							   class=""><i class="fa fa-facebook"></i></a>
							<a target="_blank"
							   href="https://twitter.com/intent/tweet?text=<?php echo get_the_title(); ?>&amp;url=<?php echo get_permalink(); ?>"
							   class=""><i class="fa fa-twitter"></i></a>
							<a target="_blank" href="https://plus.google.com/share?url=<?php echo get_permalink(); ?>"
							   class=""><i class="fa fa-google-plus"></i></a>
							<a target="_blank"
							   href="http://pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&media=<?php echo (strlen($featured_image_url) > 0) ? $featured_image_url : get_theme_mod('logo_upload', IMGURL . '/logo.png'); ?>"
							   class=""><i class="fa fa-pinterest"></i></a>
						</div>

						</main><!-- #main -->
					</div><!-- #primary -->
					<div class="post-comments">
						<?php
								// If comments are open or we have at least one comment, load up the comment template.
								if ( comments_open() || get_comments_number() ) :
									comments_template();
								endif;
							?>
					</div>
					<div class="related-post">
						<div class="row">
							<?php
	                        $args = array(
	                            'posts_per_page' => 2,
	                            'offset' => 0,
	                            'orderby' => 'rand',
	                            'post_type' => 'post',
	                            'ignore_sticky_posts' => 1,
	                            'post_status' => 'publish'
	                        );

	                        $wp_query2 = new WP_Query();
	                        $wp_query2->query($args);
	                        while ($wp_query2->have_posts()) {
	                            $wp_query2->the_post();
	                            

	                            echo '
							<div class="col-md-6">
								<div class="related-post-img">
									<a href="' . get_permalink() . '">
								';
									if (has_post_thumbnail()) {
										echo '' . the_post_thumbnail( array(200, 180) ) . '</a></div>';
									}
									echo '
										<div class="related-post_content">
											<div class="block">
												<div class="entry-title"><h1>
													<a href="' . get_permalink() . '">' . get_the_title() . '</a></h1>
												</div>
												<div class="post_excerpt">
												' . excerpt(25) . '
												</div>
												<div class="entry-footer">
													<span class="meta_dib">' . get_the_time("M d, Y") . '</span>
													<span class="meta_dib"><a href="' . get_author_posts_url(get_the_author_meta('ID')) . '">' . get_the_author_meta('display_name') . '</a></span>
													<span class="meta_dib"><a href="' . get_comments_link() . '">' . get_comments_number(get_the_ID()) . ' ' . __('Comments', 'gt3_theme_localization') . '</a></span>
												</div>
											</div>
										</div>
									</div>
								';
	                        }
	                        wp_reset_postdata();
	                        ?>
	                    </div>
	                </div>

				</div>
				<div class="col-md-4 sidebar">
					<?php get_sidebar(); ?>
					
				</div>
			</div>
		</div>
	</section>

<?php get_footer(); ?>
