<?php
/**
 * Template Name: Appointments Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
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
$apptId = $_GET['apptId'];
$loggedInUserId = get_current_user_id();
global $wpdb;
$appts = $wpdb->prefix . 'bookly_appointments';
$custAppts = $wpdb->prefix . 'bookly_customer_appointments';
$cust = $wpdb->prefix . 'bookly_customers';
$staff = $wpdb->prefix . 'bookly_staff';
// $dbquery = $wpdb->prepare( "SELECT custAppt.customer_id, appt.id, appt.start_date, appt.end_date, appt.internal_note, cust.wp_user_id, cust.name, cust.phone, cust.email, cust.notes FROM `wp_gah12_ab_customer_appointments` AS custAppt, `wp_gah12_ab_appointments` AS appt, `wp_gah12_ab_customers` AS cust WHERE custAppt.id = %d AND custAppt.appointment_id = appt.id AND custAppt.customer_id = cust.id", $apptId );
$dbquery = $wpdb->prepare("SELECT custAppt.customer_id, appt.id, appt.start_date, appt.end_date, cust.wp_user_id, custAppt.appointment_id, staff.wp_user_id AS staff_id FROM $custAppts AS custAppt, $appts AS appt, $cust AS cust, $staff AS staff WHERE appt.id = %d AND appt.id = custAppt.appointment_id AND appt.staff_id = staff.id AND cust.id = custAppt.customer_id LIMIT 1", $apptId);
$array = $wpdb->get_results($dbquery);

// echo "<pre>";
// print_r($array);
// echo "</pre>";

$staffName = get_userdata($array[0]->staff_id);
$studentName = get_userdata($array[0]->wp_user_id);

// echo "Logged in user id: $loggedInUserId <br>";
// echo "Staff id: $staffName->ID <br>";
// echo "WP User id: $studentName->ID <br>";

if($loggedInUserId == $staffName->ID || $loggedInUserId == $studentName->ID || current_user_can('fulfillment_role') || current_user_can('administrator') ) {

    $startTime = date('g:i a', strtotime($array[0]->start_date) );
    $endTime = date('g:i a', strtotime($array[0]->end_date) );

    $dbTimezone = new DateTimeZone('America/New_York');

    $studentStartTime = new DateTime($startTime, $dbTimezone);
    $studentEndTime = new DateTime($endTime, $dbTimezone);

    if($loggedInUserId == $studentName->ID) {
        $IScontactID = get_user_meta( $loggedInUserId, 'infusionsoft_user_id', true );
        if($IScontactID){
        	global $i2sdk;
        	$app = $i2sdk->isdk;

        	$userISTimeZone = $app->loadCon($IScontactID, array('TimeZone'));
        	$userISTimeZone = $userISTimeZone['TimeZone'];
            $userTimezone = new DateTimeZone($userISTimeZone);
        }

        $studentStartTime->setTimeZone($userTimezone);
        $studentEndTime->setTimeZone($userTimezone);
    } else if( current_user_can('fulfillment_role') || current_user_can('administrator') ) {
        // $userTimezone = new DateTimeZone($dbTimezone);
    }




    $studentStartTimeStr = $studentStartTime->format('g:i a');
    $studentEndTimeStr = $studentEndTime->format('g:i a');
    $timeZoneAbbr = $studentStartTime->format('T');
?>
<div id="primary" class="site-content">
    <div id="content" role="main">

    <article <?php post_class(); ?>>
        <header class="entry-header" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
            <div class="editEvent">
                <?php
                if (current_user_can("fulfillment_role")) {
                     echo '<a href="'.home_url().'/wp-admin/admin.php?page=bookly-calendar">Edit Appointment</a>';
                 } ?>
            </div>
            <h1 class="entry-title"><?php the_title(); ?></h1>
        </header>

				<subheader>
						<p class="eventDateTime">Appointment Date: <?php
                if ($apptId) {
                     echo date("jS F Y", strtotime($array[0]->start_date));
                 } else {
                     echo 'N/A';
                 } ?>
            </p>
            <p class="eventDateTime">Start Time: <?php
                if ($apptId) {
                     echo $studentStartTimeStr.' '.$timeZoneAbbr;
                 } else {
                     echo 'N/A';
                 } ?>
            </p>
            <p class="eventDateTime">End Time: <?php
                if ($apptId) {
                     echo $studentEndTimeStr.' '.$timeZoneAbbr;
                } else {
                     echo 'N/A';
                } ?>
            </p>
            <p class="eventDateTime">Staff: <?=$staffName->nickname; ?></p>
            <p class="eventDateTime">Student: <?=$studentName->nickname; ?></p>
        </subheader>

        <div class="entry-content">
            <?php
            // Get page content if there is any to allow for an editable description.
            // if (have_posts()) {
            //     while (have_posts()) {
            //         the_post();
            //         the_content();
            //     }
            // }

            if ($apptId) {
                echo $array[0]->internal_note;
            } else {
                echo "There is no appointment scheduled.";
            } ?>
        </div>

        <footer class="entry-footer">
        <?php edit_post_link(__('Edit', 'boss'), '<span class="edit-link">', '</span>'); ?>
        </footer>
    </article>
    <?php comments_template('', true); ?>

    </div><!-- #content -->
</div><!-- #primary -->
<?php } else {
    echo "<p>You don't have access to view this resource.</p>";
}
?>
 <?php get_footer(); ?>
