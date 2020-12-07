<?php
/**
 * The Template for displaying all single events.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 2.0.4
 */

get_header();

/* Banner images code Begin */
$userId = get_current_user_id();
 
 if ( $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }
 

 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

	$banner_image = get_user_meta( $user_id, 'banner_image', true );
/* Banner images code end */

// Get user role for permission purposes.
$event = get_post_meta(get_the_ID());
$allowedMemberIds = get_post_meta(get_the_ID(), '_eventMentorByName');
$memberAllowed = false;

foreach($allowedMemberIds[0] as $memberId) {
    if(in_array(get_current_user_id(), $memberId)) {
        $memberAllowed = true;
        break;
    }
}

$student_id =  $_SESSION['$user_id'];

?>

        <div id="primary" class="site-content">
            <div id="content" role="main">

                <?php while ( have_posts() ) : the_post();
                    $listByTag = get_post_meta(get_the_ID(), '_eventMentorByTag', true);
                    if(current_user_can("fulfillment_role") || memb_hasAnyTags( $listByTag ) || $memberAllowed) {

                        $eventTime = get_post_meta(get_the_ID(), 'eventHour', true) . ':' . get_post_meta(get_the_ID(), 'eventMinute', true) . ' ' . get_post_meta(get_the_ID(), 'eventAmPm', true) . ' ' . get_post_meta(get_the_ID(), 'eventTimeZone', true);
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
                        <?php /*if($student_id) { ?>
														<header class="entry-header profile-banner-on-courses-page" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
																<!-- page title -->
																<div class="profile-image"><?=get_avatar( $student_id, 96 ); ?></div>
																<h1 class="entry-title"><?php echo get_user_meta( $student_id, 'first_name', true ).' '.get_user_meta( $student_id, 'last_name', true ); ?></h1>
																<div class="profile-badges">
																	<?php
																		$badges = badgeos_get_user_achievements( array( 'user_id' => $student_id ) );

																		if ( $badges ){
																		  foreach ( $badges as $badge ){
																		    echo badgeos_get_achievement_post_thumbnail( $badge->ID );
																		  }
																		}
																	 ?>
																</div>

																<div class="message_btn">
																	<?php if ( get_current_user_id() != $student_id ){
																		echo '<a href="'.site_url().'/messages/?fepaction=newmessage&to='.$student_id.'"><button>Message</button></a>';
																	} ?>
																</div>
														</header>
												<?php }*/ ?>
												<header class="entry-header" <?php if($banner_image) { ?>  style="background-size: cover; background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
														<div class="editEvent"></div>
														<h1 class="entry-title"><?php the_title(); ?></h1>
														<div class="editEvent"></div>
												</header>
                            <header class="entry-header">
                                <p class="eventDateTime">Event Date: <?php echo get_post_meta(get_the_ID(), 'eventDate', true); ?></p>
                                <p class="eventDateTime">Event Time: <?=$eventTime;?></P>
                                <?php
                                $eventLocationLink = get_post_meta(get_the_ID(), 'eventLocationLink', true);
                                if ( $eventLocationLink ){ ?>
                                  <p class="eventDateTime">Event Location: <?php echo '<a href="'.$eventLocationLink.'" target="_blank">'.get_post_meta(get_the_ID(), 'eventLocation', true).'</a>'; ?></p>
                                <?php }else{ ?>
                                  <p class="eventDateTime">Event Location: <?php echo get_post_meta(get_the_ID(), 'eventLocation', true); ?></p>
                                <?php } ?>
                                <div class="editEvent"></div>
                            </header>
                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                            <?php
                            // If the user is a Fulfillment or a Menor, display the mentor and the students list
                            if ( current_user_can("fulfillment_role") || current_user_can("mentor_role") || current_user_can('administrator') ) {
                              echo '<div class="displayAllMentorsAndStudents">';

                              $listByName = get_post_meta(get_the_ID(), '_eventMentorByName', true);

                              if ( $listByName ) {
                                // echo '<pre>'; print_r($listByName); echo '</pre>';
                                foreach ( $listByName as $key => $mentors ) {
                                  // if there is a mentor match with the user, only display that mentor and the students
                                  if ( current_user_can("fulfillment_role") || $key == get_current_user_id() || current_user_can('administrator') ) {
                                    echo '<div class="displayEachMentorAndStudents">
                                    <p class="allClear"><strong>Attending Mentor:</strong> '.get_user_meta($key)['nickname'][0].'</p>
                                    <ul><strong>Mentoring Students:</strong>';
                                      foreach ($mentors as $studentListByMentor) {
                                          echo '<li>' . $studentListByMentor . ' &mdash; ' . get_user_meta($studentListByMentor)['nickname'][0] . '</li>';
                                      }
                                      echo '</ul>';
                                    echo '</div>';
                                  }
                                }
                              }

                              $listByTag = explode(',', $listByTag);

                              if ( $listByTag ) {
                                // echo '<pre>'; print_r($listByTag); echo '</pre>';
                                foreach ( $listByTag as $tags ) {
                                  // if there is a tag match with the user, only display that tag and the students
                                  if ( current_user_can("fulfillment_role") || $key == get_current_user_id() || current_user_can('administrator') ) {
                                    echo '<div class="displayEachMentorAndStudents">
                                    <p class="allClear"><strong>Tag:</strong></p>
                                    <ul>';
                                    if($tags) {
                                        echo '<li>' . $tags . ' &mdash; ' . do_shortcode('[memb_tag_name tagids="' . $tags . '" ]') . '</li>';
                                    }
                                    echo '</ul>';
                                    echo '</div>';
                                  }
                                }
                              }

                              echo '</div>';
                            }
                            ?>

                            <?php if ( current_user_can("fulfillment_role") || current_user_can('administrator') ) {
                              echo '<div class="deleteEvent"><span class="deleteThis">DELETE EVENT</span></div>';
                            } ?>

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
