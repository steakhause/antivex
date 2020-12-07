<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header(); ?>
<?php $all_keys = get_post_custom_keys(); ?>
<?php if(is_array($all_keys) && ( in_array('_badgeos_points', get_post_custom_keys()) || in_array('_badgeos_hidden', get_post_custom_keys()) )) : ?>

    <?php if ( is_active_sidebar('sensei-default') || is_active_sidebar('learndash-default') ) : ?>
        <div class="page-right-sidebar">
    <?php else : ?>
        <div class="page-full-width">
    <?php endif; ?>
        <div id="primary" class="site-content single-badgeos">

            <div id="content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <div class="table">
                        <div class="badgeos-single-image table-cell">
                            <?php //echo badgeos_get_achievement_post_thumbnail( get_the_ID(), 'medium' ) ?>
                        <?php
                            if ( has_post_thumbnail() ){
                                $id = get_post_thumbnail_id($post->ID, 'medium');
                                echo '<img src="' . wp_get_attachment_url($id) . '" />';
                            }
                        ?>
                        </div>
                        <header class="entry-header table-cell">
                           <?php
                            // Points for badge
                            echo badgeos_achievement_points_markup();
                            ?>

                            <?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>

                            <?php if ( 'post' == get_post_type() ) : ?>
                            <div class="entry-meta">
                                <?php underscores_posted_on(); ?>
                            </div><!-- .entry-meta -->
                            <?php endif; ?>
                        </header><!-- .entry-header -->
                    </div>

                    <div class="entry-content">
                        <?php
                            /* translators: %s: Name of current post */
                            the_content( sprintf(
                                __( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'boss' ),
                                the_title( '<span class="screen-reader-text">"', '"</span>', false )
                            ) );
                        ?>

                        <?php
                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ),
                                'after'  => '</div>',
                            ) );
                        ?>
                    </div><!-- .entry-content -->

                    <footer class="entry-footer">
                    </footer><!-- .entry-footer -->
                </article><!-- #post-## -->

                <?php the_post_navigation(); ?>

                <?php
                    // If comments are open or we have at least one comment, load up the comment template
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                ?>

            <?php endwhile; // end of the loop. ?>

            </div><!-- #main -->
        </div><!-- #primary -->
        <?php if ( is_active_sidebar('sensei-default') || is_active_sidebar('learndash-default') ) : ?>
            <!-- default WordPress sidebar -->
            <div id="secondary" class="widget-area" role="complementary">
                <?php
                    if ( is_active_sidebar('sensei-default') ){
                        dynamic_sidebar( 'sensei-default' );
                    } else {
                        dynamic_sidebar( 'learndash-default' );
                    }
                ?>
            </div><!-- #secondary -->
        <?php endif; ?>
    </div>
<?php else: ?>
    <?php while ( have_posts() ) : the_post(); ?>
<?php

$currcourse = get_post_meta( $post->ID, 'course_id', TRUE);
$currlesson = get_post_meta( $post->ID, 'lesson_id', TRUE);

$currcourse_data = get_post($currcourse);

$course_name = '<a href="'.get_the_permalink(get_post_meta( $post->ID, 'course_id', TRUE)).'">'.get_the_title($currcourse).'</a>';
$lesson_name = '<a href="'.get_the_permalink(get_post_meta( $post->ID, 'lesson_id', TRUE)).'">'.get_the_title($currlesson).'</a>';

?>
        <?php
        $style = 'style="background-image: url(' . get_the_post_thumbnail_url( $currcourse ) . '); background-position: right 40px center;background-size: contain;background-repeat: no-repeat;" data-photo="yes"';
        ?>


<!-- This section was after header before -->
<?php
        if ( is_active_sidebar( 'sidebar' ) ) :
            echo '<div class="page-right-sidebar">';
        else :
            echo '<div class="page-full-width">';
        endif;
        ?>

        <div id="primary" class="site-content">
            <div id="content" role="main">

<!-- This section was after header before -->


        <header class="1 <?php echo $currcourse_data->post_name; ?> page-cover table">
            <div class="table-cell page-header" <?php echo $style; ?>>
                <div class="cover-content">
                    <h1 class="post-title main-title">
                    	<?php the_title(); ?>

                    	<small style="font-size: 16px; display: block;"><?php echo  $course_name; ?> <span style="font-size: 23px;">›</span> <?php echo $lesson_name;  ?> <span style="font-size: 23px;">›</span> <?php echo get_the_title(); ?></small>

                    </h1>

                </div>
            </div>
        </header><!-- .archive-header -->

        
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<!-- Search, Blog index, archives -->
	<?php if ( is_search() || is_archive() || is_home() ) : // Only display Excerpts for Search, Blog index, and archives ?>

		<?php if ( has_post_thumbnail() ) : ?>
			<a class="entry-post-thumbnail" href="<?php the_permalink(); ?>">
				<?php
				$thumb	 = get_post_thumbnail_id();
				$image	 = buddyboss_resize( $thumb, '', 2.5, null, null, true );
				?>
				<img src="<?php echo $image[ 'url' ]; ?>" alt="<?php the_title(); ?>"/>

			</a>
		<?php else : ?>
			<div class="whitespace"></div>
		<?php endif; ?>

		<div class="post-wrap">

			<header>
				<h1 class="entry-title">
					<a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', 'boss' ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h1>
			</header><!-- .entry-header -->


			<div class="entry-meta mobile">
				<?php buddyboss_entry_meta( false ); ?>
			</div>

			<div class="entry-content entry-summary <?php if ( has_post_thumbnail() ) : ?>entry-summary-thumbnail<?php endif; ?>">

				<?php
						//entry-content
						if ( 'excerpt' === boss_get_option( 'boss_entry_content' ) ):
				            the_excerpt();
						else:
				            the_content();
				        endif;
				?>

				<footer class="entry-meta table">

					<div class="table-cell desktop">
						<?php buddyboss_entry_meta(); ?>
					</div>

					<div class="mobile">
						<?php buddyboss_entry_meta( true, false, false ); ?>
					</div>

					<span class="entry-actions table-cell mobile">
						<?php if ( comments_open() ) : ?>
							<?php comments_popup_link( '', '', '', 'reply', '' ); ?>
						<?php endif; // comments_open() ?>
					</span><!-- .entry-actions -->

				</footer><!-- .entry-meta -->

			</div><!-- .entry-content -->

		</div><!-- .post-wrap -->

		<!-- all other templates -->
	<?php else : ?>

		<div class="entry-content">
			<?php  the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'boss' ) ); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->
		<?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>

	<?php endif; ?>


</article><!-- #post -->

                <?php comments_template( '', true ); ?>

            </div><!-- #content -->
        </div><!-- #primary -->

        <?php
    endwhile;

    // if ( is_active_sidebar( 'sidebar' ) ) :
    //     get_sidebar( 'sidebar' );
    // endif;
    ?>
    </div><!-- page-right-sidebar/page-full-width -->
<?php endif; ?>

<?php get_footer();
