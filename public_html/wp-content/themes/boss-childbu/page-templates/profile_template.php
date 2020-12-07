<?php
/**
 * Template Name: Profile Page Template
 *
 * Description: Use this page template for assign homework page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

?>

<div class="partial-outer">
		<div id="content" role="main">
			 <div id="primary" class="site-content">

				  <div class="entry-content">
					  <?php while ( have_posts() ) : the_post(); ?>
							<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
								<header class="entry-header <?php if(is_search()){ echo 'page-header'; }?>" style="background: url(<?php echo get_template_stylesheet_uri(); ?>images/partials-bg.jpg) no-repeat right center;">
									<!-- <h1 class="entry-title <?php if(is_search()){ echo 'main-title'; }?>"><?php the_title(); ?></h1> -->
									<img src="https://learn.cleverinvestor.com/wp-content/uploads/2018/01/crypto-alliance-guide_logo.png" alt="site logo">
								</header>
							<div class="entry-content">
								<?php the_content(); ?>
								<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ), 'after' => '</div>' ) ); ?>
							</div><!-- .entry-content -->

							<footer class="entry-meta">
								<?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
							</footer><!-- .entry-meta -->

						</article><!-- #post -->
						<?php endwhile; // end of the loop. ?>
				  </div>
		  </div>
  </div>
</div>

<?php
get_footer();

 ?>
