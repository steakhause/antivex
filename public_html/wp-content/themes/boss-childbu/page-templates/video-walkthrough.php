<?php
/**
 * Template Name: Video Walkthrough Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 get_header();
?>

    <div id="page-documents" class="page-documents">

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

                <?php // Get page content if there is any to allow for an editable description.
          				if ( have_posts() ) {
          					while ( have_posts() ) {
          						the_post();
          						the_content();
          					}
          				}
                ?>

                <?php
                  global $post;

                  $current = get_the_ID($post->ID);
                  $catargs = array(
                      'child_of'      => 0,
                      'orderby'       => 'name',
                      'order'         => 'ASC',
                      'hide_empty'    => 1,
                      'taxonomy'      => 'list'
                  );

                  foreach ( get_categories( $catargs ) as $tax ) {
                    $args = array (
                      'post_type' => 'video_walkthrough',
                      'order' => 'ASC',
                      'tax_query' => array (
                        array (
                          'taxonomy' => 'list',
                          'field' => 'slug',
                          'terms' => $tax->slug
                        )
                      ),
                    );

                    if ( get_posts( $args ) && has_video_walkthrough_access( $args ) ) {
                      echo '<div class="postsByList">
                      <h4>'.$tax->name.'</h4>';
                      foreach( get_posts( $args ) as $posts ) {
                        if ( memb_hasPostAccess( $posts->ID ) ) {
                          echo '<div>
                            <a href="'.get_permalink( $posts ).'">'.$posts->post_title.'</a><br />
                            <p>'.$posts->post_excerpt.'</p>';

                            $videoLink = explode('com/', get_post_meta($posts->ID, 'video_walkthrough_link', true));
                            ?>
                            <iframe src="https://player.vimeo.com/video/<?= $videoLink[1]; ?>" width="64" height="36" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            <?php
                          echo '</div>';
                        }
                      }
                      echo '</div>';
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
