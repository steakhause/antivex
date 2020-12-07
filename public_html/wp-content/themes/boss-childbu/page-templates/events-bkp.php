<?php
/**
 * Template Name: Events Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
get_header();

//1327710

$args = array(
	'meta_key'     => 'infusionsoft_user_id',
	'meta_value'   => 1327710,
 );

 $wpId = new WP_User_Query( $args );

  if ( ! empty( $wpId->results ) ) {
 	 foreach ( $wpId->results as $user ) {
 	   $userId = $user->ID;
 	 }
 	}
?>

<div class="<?php echo get_page_template_slug(); ?>">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						// get_template_part( 'content', 'page' );
					}
				}
			?>

			<?php
			// echo '<pre>'; print_r(get_user_meta(17)); echo '</pre>';
			echo get_user_meta(17)['mentorRole'][0];
				// $eventPostId = 120;
				// $userId = 17;
				//
				//
				// echo '<pre>'; print_r(unserialize(get_post_meta($eventPostId, '_eventMentors', true))); echo '</pre>';
				// $variables = unserialize(get_post_meta($eventPostId, '_eventMentors', true));
				// foreach ($variables as $keys => $values) {
				// //foreach ($values as $key => $value) {
				// 	echo '<pre>'; print_r($keys); echo '</pre>';
				// //}
				// }
				// echo update_profile_event_fields(17, 456);
				//update_profile_event_fields(17, 456);
				// echo '<pre>'; print_r(get_user_meta($userId, 'registeredEvent')); echo '</pre>';

				$variable = get_users(array('role' => 'subscriber'));
				// echo '<pre>'; print_r($variable); echo '</pre>';
				foreach ($variable as $key => $value) {
					$studentId = $value->ID;
					$studentMeta = get_user_meta($studentId);
					// echo '<pre>'; print_r($studentMeta); echo '</pre>';
					// $studentMetaMentor = $studentMeta['nickname'][0];
					// echo $studentMetaMentor;
					// $studentMetaMentorID = explode(' - ', $studentMetaMentor)[0];
					// echo '<pre>'; print_r(explode(' - ', $studentMetaMentor)); echo '</pre>';
				}
				// echo '<pre>'; print_r(get_user_meta(17)); echo '</pre>';
				// echo (get_user_meta(17))['infusionsoft_user_id'][0];

				$user = (wp_get_current_user()->roles)[0];
				$args = array (
			    'post_type' => 'Event',
					'posts_per_page' => 100,
			    'order' => 'ASC'
				);

				$the_query = new WP_Query( $args );

				if ( $the_query->have_posts() ) {
					echo "<ul>";
					while ( $the_query->have_posts() ) {
						$the_query->the_post();
						echo '<li class="listedEvent">'; ?>
							<!-- <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> -->
							<h4 class="eventName"><?php echo get_post_meta($post->ID, 'eventTitle', true); ?></h4>
							<div class="eventBody">
								<div class="eventDescription"><?php echo get_post_meta($post->ID, 'eventDescription', true); ?></div>
								<div class="eventDateTime"><?php echo get_post_meta($post->ID, 'eventDate', true).' - '.get_post_meta($post->ID, 'eventHour', true).':'.get_post_meta($post->ID, 'eventMinute', true).' '.get_post_meta($post->ID, 'eventAmPm', true).' '.get_post_meta($post->ID, 'eventTimeZone', true); ?></div>
							</div>

							<?php
								// echo get_post_meta($post->ID, 'eventLocation', true).' > ';
								// echo '<pre>'; print_r(unserialize(get_post_meta($post->ID, '_eventMentoringStudents', true))); echo '</pre>';
								$mentorByName = unserialize(get_post_meta($post->ID, '_eventMentorsByName', true));
								$mentorByTag = unserialize(get_post_meta($post->ID, '_eventMentorsByTag', true));

								// echo '<pre>'; print_r($mentorByName); echo '</pre>';
								// echo '<pre>'; print_r($mentorByTag); echo '</pre>';
							?>
							<?php if ( $user=="fulfillment_role" ) { ?>
								<a href="<?php the_permalink(65); ?>?eventId=<?php the_ID(); ?>" class="editEvent">edit</a> <!--// because the user is fulfillment and events exist, user can edit the event-->
							<?php }
						echo '</li>';
					}
					echo "</ul>";
				}
			?>

			<?php if ( $user=="fulfillment_role" ) { ?>
				<p class="addEvent"><a href="<?php the_permalink(65); ?>">Add Event</a></p>
			<?php } ?>

		</div><!-- #content -->
	</div><!-- #primary -->
  <?php get_sidebar(); ?>

</div>
<?php get_footer(); ?>
