<?php
/**
 * Template Name: Courses Page Template
 *
 * Description: Use this page template for courses page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

$userId = get_current_user_id();

 if ( isset($_GET['user_id'] )) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }


 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

 $banner_image = get_user_meta( $user_id, 'banner_image', true );


?>

<div class="courses">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article <?php post_class(); ?>>
					<header class="entry-header" <?php if($banner_image) { ?>   style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover;" <?php } ?>>
							<h1 class="entry-title"><?php the_title(); ?></h1>
					</header>
      </article>

      <?php

      $args = array(
        'post_type' => 'sfwd-courses',
        'posts_per_page' => -1
      );

     // echo do_shortcode( '[memb_set_prohibited_action action=hide]');
      // The Query
      $the_query = new WP_Query( $args );

      // The Loop
      if ( $the_query->have_posts() ) {
      	echo '<ul>';
      	while ( $the_query->have_posts() ) {
      		
      		$the_query->the_post();
      		if(get_field('external_url') == '') {
      				$target="";
      				$course_link = get_permalink();
      			}
      		else {
      			$course_link = get_field('external_url');
      			$target="_blank";
      		}
      		$thumbnail = get_the_post_thumbnail_url( '','post-thumbnail' );
      		if($thumbnail == '') { $thumbnail = get_stylesheet_directory_uri().'/images/placeholder.jpg';}
      		echo '<li class="course-list"><p><a target="'.$target.'" class="course-thumbnail" href="'.$course_link.'" style="background: url('.$thumbnail.') center center no-repeat"></a></p>';
          echo do_shortcode('[learndash_course_progress course_id="'.get_the_ID().'"]');
          echo '<p><a target="'.$target.'" href="'.$course_link.'">' . get_the_title() . '</a></p>';
          echo '</li>';
      	}
      	echo '</ul>';
      	/* Restore original Post Data */
      	wp_reset_postdata();
      } else {
      	echo '<p>You do not have access to any courses.';
      }

      ?>

    </div>
  </div>

</div>

<?php

get_footer();
 ?>
