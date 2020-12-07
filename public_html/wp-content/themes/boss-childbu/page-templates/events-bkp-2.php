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

// Get user role for permission purposes.
$loggedInUserId = get_current_user_id();

$pastEvents = [];

$userData = get_userdata($loggedInUserId);
$userRoles = $userData->roles;

if( in_array( 'fulfillment_role', $userRoles) || in_array('mentor_role', $userRoles) || in_array('administrator', $userRoles) ) {
    $sqlSearchTerm = 'staff.wp_user_id = %d';
} else {
    $sqlSearchTerm = 'cust.wp_user_id = %d';
}

if ( current_user_can("mentoring_student_role") ){
  $IScontactID = get_user_meta( $loggedInUserId, 'infusionsoft_user_id', true );

  if($IScontactID){

  	global $i2sdk;
  	$app = $i2sdk->isdk;

  	$userISTimeZone = $app->loadCon($IScontactID, array('TimeZone'));


  	$userISTimeZone = $userISTimeZone['TimeZone'];

    $dbTimezone = new DateTimeZone('America/New_York');
    $userTimezone = new DateTimeZone($userISTimeZone);
    // $appointmentDate = new DateTime($appointmentDate, $dbTimezone);
    // $appointmentDate->setTimeZone($userTimezone);
    // $appointment = $appointmentDate->format('Y-m-d H:i:s');
  }
}


?>

<div class="events">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article <?php post_class(); ?>>
					<header class="entry-header">
							<div class="editEvent"></div>
							<h1 class="entry-title"><?php the_title(); ?></h1>
							<div class="editEvent"></div>
					</header>

					<div class="entry-content">
						<?php
							if($_GET['action']=="successful") {
								echo '<p class="actionMessage actionSuccessful">Event was deleted successfully.</p>';
							}
							if($_GET['action']=="unsuccessful") {
								echo '<p class="actionMessage actionUnsuccessful">Event was not deleted successfully.</p>';
							}

							// Get page content if there is any to allow for an editable description.
							if ( have_posts() ) {
								while ( have_posts() ) {
									the_post();
									the_content();
								}
							}

							// If the user is Fulfillment, allow adding new events
							if ( current_user_can("fulfillment_role") || current_user_can('administrator') ) {
								echo '<p class="addEvent"><a href="'.get_permalink(65).'">Add Event</a></p>';
							}

							echo '<hr />';

							// Generate an array for the list of events
							$events = get_events();

							$eventDate = $event['date'];

							// Generate an array for the list of appointments, set from Bookly, useing wpdb prepared statement
							global $wpdb;
							$appts = $wpdb->prefix . 'bookly_appointments';
                            $custAppts = $wpdb->prefix . 'bookly_customer_appointments';
                            $cust = $wpdb->prefix . 'bookly_customers';
                            $staff = $wpdb->prefix . 'bookly_staff';
							// $dbquery = $wpdb->prepare( "SELECT custAppt.customer_id, appt.id, appt.start_date, appt.end_date, appt.internal_note, cust.wp_user_id, cust.name, cust.phone, cust.email, cust.notes, staff.wp_user_id AS staff_id FROM `wp_gah12_ab_customer_appointments` AS custAppt, `wp_gah12_ab_appointments` AS appt, `wp_gah12_ab_customers` AS cust, `wp_gah12_ab_staff` AS staff WHERE custAppt.appointment_id = appt.id AND custAppt.customer_id = cust.id && staff.id = appt.staff_id && cust.wp_user_id = %s OR staff.wp_user_id = %s", $loggedInUserId, $loggedInUserId );
              $query = "SELECT custAppt.customer_id, appt.id, appt.start_date, appt.end_date, appt.internal_note, cust.wp_user_id, cust.name, cust.phone, cust.email, cust.notes, staff.wp_user_id AS staff_id FROM $custAppts AS custAppt, $appts AS appt, $cust AS cust, $staff AS staff WHERE custAppt.appointment_id = appt.id AND custAppt.customer_id = cust.id AND staff.id = appt.staff_id AND $sqlSearchTerm";
							$dbquery = $wpdb->prepare( $query, $loggedInUserId);
							$wpquery = $wpdb->get_results($dbquery);

							// empty array to store both values
							$arrays = array();

							if ( $events || $wpquery ) {
								// loop through the events and store them into the empty array with date as the key
								foreach ( $events as $event_id => $event ) {
									$key = date("Y-m-d", strtotime($event['date'])).'<br>';
                  $event['post_id'] = $event_id;
									$arrays[$key] = $event;
								}

								// loop through the appointments and store them into the empty array with date as the key
								foreach ( $wpquery as $key => $query ) {
									$keyValue = explode(" ", $query->start_date)[0];
									$arrays[$keyValue] = $query;
								}

								// sort the array by the key
								ksort($arrays);

                                echo "<pre>";
                                print_r($arrays);
                                echo "</pre>";

							// Display the title and link of the events and appointments in dated order to the event single,
							// the event and appoinment content excerpt and the date for any viewer
							echo '<h2>Upcoming Events</h2>';
							echo '<ul class="eventList">';
								foreach ( $arrays as $dateKey => $array ) {
                                    if(array_key_exists('event_tags', $array)) {
                                        $tags = $array['event_tags'];
                                    }

                                    if(array_key_exists('event_mentors', $array)) {
                                        $mentorList = $array['event_mentors'];
                                    }

                                    if(property_exists($array, 'staff_id')) {
                                        $staffId = $array->staff_id;
                                    }
                                    if(property_exists($array, 'wp_user_id')) {
                                        $wordPressUserId = $array->wp_user_id;
                                    }

                                    $showEvent = false;

                                    if(current_user_can("fulfillment_role") || array_key_exists($loggedInUserId, $tags) || array_key_exists($loggedInUserId, $mentorList) || $array->staff_id == $loggedInUserId || $array->wp_user_id == $loggedInUserId)
                                    {
                                        $showEvent = true;
                                    } elseif (memb_hasAnyTags($tags))
                                    {
                                        $showEvent = true;
                                    } else
                                    {
                                        if(!$showEvent) {
                                            foreach($mentorList as $listUser) {
                                                if(in_array($loggedInUserId, $listUser)) {
                                                    $showEvent = true;
                                                    break;
                                                }
                                            }
                                        }
                                    }

                                    if($showEvent) {
    									echo '<li class="listedEvent">';
    									// if the array has an 'address' key, it is an event
    									if (array_key_exists("address", $array)) {
                                            if($dateKey >= date('Y-m-d',strtotime("today"))) {
                                                $eventTime = $array['hour'] . ':' . $array['minute'] . ' ' . $array['pm'] . ' ' . $array['time_zone'];
                                                echo '
        										<h4 class="eventName"><a href="'.$array['address'].'">'.$array['name'].'</a></h4>
        										<p class="eventDate"><strong>Event Date:</strong> '.date("jS F Y", strtotime($array['date'])).'</p>
                                                <p><strong>Event Time:</strong> ' . $eventTime . '</p>
        										<div class="eventBody">
        											<div class="eventDescription">'.substr($array['description'], 0, 500).' ... <a href="'.$array['address'].'">View More Info</a></div>';
                              if ( current_user_can("administrator") || current_user_can("fulfillment_role") ){
                                echo '<a href="'.get_permalink(65).'?clone=1&eventId='.$array['post_id'].'" class="button">Clone Event</a>';
                              }
        										echo '</div>';
        										echo '</li>';

        										echo '<hr />';
                                            } else {
                                                $pastEvents[$dateKey] = $array;
                                            }

    									// if the array does not have an 'address' key, it is an appointment
    									} else {
                                            if($dateKey >= date('Y-m-d',strtotime("today"))) {
                                                $start_date = explode(" ", $array->start_date)[0];
        										$date = date("jS F Y", strtotime($start_date));
                                                $startTime = date('g:i a', strtotime($array->start_date) );
                                                $endTime = date('g:i a', strtotime($array->end_date) );
                                                $staffName = get_userdata($array->staff_id);
                                                $studentName = get_userdata($array->wp_user_id);

                                                $studentProfileLink = add_query_arg([
                                                    'user_id' => $studentName->ID
                                                ], get_permalink(819));

                            $studentStartTime = new DateTime($startTime, $dbTimezone);
                            $studentStartTime->setTimeZone($userTimezone);
                            $studentStartTimeStr = $studentStartTime->format('g:i a');
                            $timeZoneAbbr = $studentStartTime->format('T');

                            $studentEndTime = new DateTime($endTime, $dbTimezone);
                            $studentEndTime->setTimeZone($userTimezone);
                            $studentEndTimeStr = $studentEndTime->format('g:i a');

        										echo '
        										<h4 class="eventName"><a href="'.get_permalink(366).'?apptId='.$array->id.'"> ' . $date . ' &mdash; Mentoring Call</a></h4>
                                                <div class="left-col"><p class="eventDate"><strong>Start Time:</strong> '.$studentStartTimeStr.' '.$timeZoneAbbr.'</p>
                                                <p class="eventDate"><strong>End Time:</strong> '.$studentEndTimeStr.' '.$timeZoneAbbr.'</p></div>
                                                <div class="right-col"><p class="eventDate"><strong>Staff:</strong> ' . $staffName->nickname . '</p>
                                                <p class="eventDate"><strong>Student:</strong> <a href="' . $studentProfileLink . '" target="_blank">' . $studentName->nickname . '</a></p></div>
        										<div class="eventBody">
        											<div class="eventDescription">'.substr($array->internal_note, 0, 500).' ... <a href="'.get_permalink(366).'?apptId='.$array->id.'">View More Info</a></div>';
        										echo '</div>';
        										echo '</li>';

        										echo '<hr />';
                                            } else {
                                                $pastEvents[$dateKey] = $array;
                                            }
    									}
                                    }


								}
								echo "</ul>";

                                echo '<h2>Past Events</h2>';
                                echo '<ul class="eventList">';
                                foreach($pastEvents as $dateKey => $event) {
                                    echo '<li class="listedEvent">';
                                    // if the array has an 'address' key, it is an event
                                    if (array_key_exists("address", $event)) {
                                        $eventTime = $event['hour'] . ':' . $event['minute'] . ' ' . $event['pm'] . ' ' . $event['time_zone'];
                                        echo '
                                        <h4 class="eventName"><a href="'.$event['address'].'">'.$event['name'].'</a></h4>
                                        <p class="eventDate"><strong>Event Date:</strong> '.date("jS F Y", strtotime($event['date'])).'</p>
                                        <p><strong>Event Time:</strong> ' . $eventTime . '</p>
                                        <div class="eventBody">
                                            <div class="eventDescription">'.substr($event['description'], 0, 500).' ... <a href="'.$event['address'].'">View More Info</a></div>';
                                            if ( current_user_can("administrator") || current_user_can("fulfillment_role") ){
                                              echo '<a href="'.get_permalink(65).'?clone=1&eventId='.$event['post_id'].'" class="button">Clone Event</a>';
                                            }
                                        echo '</div>';
                                        echo '</li>';

                                        echo '<hr />';

                                    // if the array does not have an 'address' key, it is an appointment
                                    } else {
                                        $start_date = explode(" ", $event->start_date)[0];
                                        $date = date("jS F Y", strtotime($start_date));
                                        $staffName = get_userdata($event->staff_id);
                                        $studentName = get_userdata($event->wp_user_id);
                                        // echo '
                                        // <h4 class="eventName"><a href="'.get_permalink(366).'?apptId='.$event->id.'">Appointment</a></h4>
                                        // <h6 class="eventDate">'.$date.'</h6>
                                        // <div class="eventBody">
                                        //     <div class="eventDescription">'.substr($event->internal_note, 0, 500).' ... <a href="'.get_permalink(366).'?apptId='.$event->id.'">View More Info</a></div>
                                        // </div>';
                                        $studentStartTime = new DateTime($startTime, $dbTimezone);
                                        $studentStartTime->setTimeZone($userTimezone);
                                        $studentStartTimeStr = $studentStartTime->format('g:i a');
                                        $timeZoneAbbr = $studentStartTime->format('T');

                                        $studentEndTime = new DateTime($endTime, $dbTimezone);
                                        $studentEndTime->setTimeZone($userTimezone);
                                        $studentEndTimeStr = $studentEndTime->format('g:i a');

                                        echo '
                                        <h4 class="eventName"><a href="'.get_permalink(366).'?apptId='.$event->id.'"> ' . $date . ' &mdash; Mentoring Call</a></h4>
                                        <div class="left-col"><p class="eventDate"><strong>Start Time:</strong> '.$studentStartTimeStr.' '.$timeZoneAbbr.'</p>
                                        <p class="eventDate"><strong>End Time:</strong> '.$studentEndTimeStr.' '.$timeZoneAbbr.'</p></div>
                                        <div class="right-col"><p class="eventDate"><strong>Staff:</strong> ' . $staffName->nickname . '</p>
                                        <p class="eventDate"><strong>Student:</strong> <a href="' . $studentProfileLink . '" target="_blank">' . $studentName->nickname . '</a></p></div>
                                        <div class="eventBody">
                                            <div class="eventDescription">'.substr($event->internal_note, 0, 500).' ... <a href="'.get_permalink(366).'?apptId='.$event->id.'">View More Info</a></div>
                                        </div>';
                                        echo '</li>';

                                        echo '<hr />';
                                    }
                                }
							}
						?>
					</div>

					<footer class="entry-footer">
							<?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
					</footer>
			</article>
			<?php comments_template( '', true ); ?>

	</div><!-- #primary -->
  <?php // get_sidebar(); ?>

</div>
<?php get_footer(); ?>
