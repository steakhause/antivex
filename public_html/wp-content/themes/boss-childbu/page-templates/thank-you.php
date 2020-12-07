<?php
/**
 * Template Name: Thank You Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

wp_redirect(get_permalink( get_page_by_path( 'support' ) ).'?action=success');

die;

 get_header();
?>

    <div id="user-settings" class="user-settings">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header">
                  <!-- page title -->
                  <div class="editEvent"></div>
                  <h1 class="entry-title"><?php the_title(); ?></h1>
                  <div class="editEvent"></div>
              </header>

              <div class="entry-content">
                <?php
                  // Get page content if there is any to allow for an editable description.
          				if ( have_posts() ) {
          					while ( have_posts() ) {
          						the_post();
          						the_content();
          					}
          				}
                ?>
              </div>

              <footer class="entry-footer">
                  <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
              </footer>
          </article>
          <?php comments_template( '', true ); ?>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>

  </div>

 <?php get_footer(); ?>
