<?php
/**
 * The Template for displaying all single video Walkthroughs.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 2.0.4
 */

get_header(); ?>

        <div id="primary" class="site-content">
            <div id="content" role="main">

                <?php while ( have_posts() ) : the_post();
                    $listByTag = get_post_meta(get_the_ID(), '_eventMentorByTag', true);
                    if($user_role == "fulfillment_role" || memb_hasAnyTags( $listByTag )) {
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="entry-header">
                                <div class="editEvent"></div>
                                <h1 class="entry-title"><?php the_title(); ?></h1>
                                <div class="editEvent"></div>
                            </header>
                            <div class="entry-content">
                              <?php
                              the_content();

                              $url = get_post_meta( get_the_ID(), 'video_walkthrough_link', true );

                              $vidId = explode('com/', $url)[1];
                              ?>
                              <iframe src="https://player.vimeo.com/video/<?php echo $vidId; ?>" width="640" height="360" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
                            </div>



                            <footer class="entry-footer">
                                <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
                            </footer>
                        </article>
                        <?php comments_template( '', true );
                    } else {
                        echo '<p class="unauthorized">You are not authorized to view this page.</p>';
                    } ?>
                <?php endwhile; // end of the loop. ?>

            </div><!-- #content -->
        </div><!-- #primary -->

    </div>

<?php get_footer(); ?>
