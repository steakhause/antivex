<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

/**
 * Template Name: Book Appointment Page
 *
 * Description: Use this page template for the book appointments page.
 *
 * @package    WordPress
 * @subpackage Boss
 * @since      Boss 1.0.0
 */

get_header();

$student_id = sanitize_text_field($_GET['student_id']);
$mentor_id = (int)get_user_meta($student_id, '_assignedMentor', true);
$student_name = get_user_meta($student_id, 'first_name', true) . ' ' . get_user_meta($student_id, 'last_name', true);

$getContactId = get_user_meta($student_id, 'infusionsoft_user_id', true);
$email = do_shortcode('[memb_contact contact_id="' . $getContactId . '" fields="Email"]');
// $phone = do_shortcode('[memb_contact contact_id="' . $getContactId . '" fields="Phone1"]');
// $phone = ( $phone ? $phone : '' );
// $phone = '';

global $wpdb;
$appts = $wpdb->prefix . 'bookly_appointments';
$custAppts = $wpdb->prefix . 'bookly_customer_appointments';
$cust = $wpdb->prefix . 'bookly_customers';
$bookDBStaff = $wpdb->prefix . 'bookly_staff';
$query_string = "SELECT id FROM $bookDBStaff WHERE wp_user_id = %d LIMIT %d";

$query = $wpdb->prepare($query_string, $mentor_id, 1);
$results = $wpdb->get_results($query, 'ARRAY_A');

$staff_id = $results[0]['id'];

// $TESTCASES = array("Pacific/Pago_Pago",
// "America/Adak",
// "Pacific/Honolulu",
// "Pacific/Marquesas",
// "America/Anchorage",
// "Pacific/Gambier",
// "America/Santa_Isabel",
// "America/Los_Angeles",
// "Pacific/Pitcairn",
// "America/Mazatlan",
// "America/Denver",
// "America/Phoenix",
// "America/Chicago",
// "America/Mexico_City",
// "America/Guatemala",
// "Pacific/Easter",
// "America/New_York",
// "America/Havana",
// "America/Bogota",
// "America/Caracas",
// "America/Halifax",
// "America/Goose_Bay",
// "America/Santo_Domingo",
// "America/Santiago",
// "America/Asuncion",
// "America/Campo_Grande",
// "Atlantic/Stanley",
// "America/St_Johns",
// "America/Godthab",
// "America/Miquelon",
// "America/Sao_Paulo",
// "America/Montevideo",
// "America/Argentina/Buenos_Aires",
// "America/Noronha",
// "Atlantic/Azores",
// "Atlantic/Cape_Verde",
// "UTC",
// "Europe/London",
// "Africa/Windhoek",
// "Africa/Lagos",
// "Europe/Berlin",
// "Africa/Cairo",
// "Asia/Gaza",
// "Africa/Johannesburg",
// "Asia/Beirut",
// "Asia/Damascus",
// "Europe/Istanbul",
// "Asia/Jerusalem",
// "Asia/Baghdad",
// "Europe/Minsk",
// "Asia/Tehran",
// "Europe/Moscow",
// "Asia/Dubai",
// "Asia/Yerevan",
// "Asia/Baku",
// "Asia/Kabul",
// "Asia/Karachi",
// "Asia/Kolkata",
// "Asia/Kathmandu",
// "Asia/Dhaka",
// "Asia/Yekaterinburg",
// "Asia/Rangoon",
// "Asia/Jakarta",
// "Asia/Omsk",
// "Asia/Shanghai",
// "Asia/Krasnoyarsk",
// "Australia/Eucla",
// "Asia/Irkutsk",
// "Asia/Tokyo",
// "Australia/Darwin",
// "Australia/Adelaide",
// "Asia/Yakutsk",
// "Australia/Sydney",
// "Australia/Brisbane",
// "Australia/Lord_Howe",
// "Asia/Vladivostok",
// "Pacific/Noumea",
// "Pacific/Norfolk",
// "Pacific/Tarawa",
// "Asia/Kamchatka",
// "Pacific/Fiji",
// "Pacific/Auckland",
// "Pacific/Majuro",
// "Pacific/Chatham",
// "Pacific/Tongatapu",
// "Pacific/Apia",
// "Pacific/Kiritimati");

$IScontactID = get_user_meta($student_id, 'infusionsoft_user_id', true);

if ($IScontactID) {

    global $i2sdk;
    $app = $i2sdk->isdk;

    $userISTimeZone = $app->loadCon($IScontactID, array('TimeZone'));

    $userISTimeZone = $userISTimeZone['TimeZone'];
}

if (!$userISTimeZone) {
    $userISTimeZone = 'America/New_York';
}

//IS TIme Zone Field WILL GO HERE
$dateTimeZoneUser = new DateTimeZone($userISTimeZone);

$dateTimeZoneUTC = new DateTimeZone("UTC");
$dateTimeUTC = new DateTime("now", $dateTimeZoneUTC);

$timeOffset = $dateTimeZoneUser->getOffset($dateTimeUTC);

if ($timeOffset) {
    //FLip bit
    $timeOffset = $timeOffset * -1;

    //Convert to mins
    $timeOffset = $timeOffset / 60;
}

//Default to Eastern
if (!$timeOffset && $timeOffset !== 0) {

    $dateTimeZoneDefault = new DateTimeZone("America/New_York");
    $dateTimeDefault = new DateTime("now", $dateTimeZoneDefault);

    $timeOffset = $dateTimeZoneUser->getOffset($dateTimeDefault);

    if ($timeOffset) {
        //FLip bit
        $timeOffset = $timeOffset * -1;

        //Convert to mins
        $timeOffset = $timeOffset / 60;
    }

    //If we still dont have a time
    if (!$timeOffset) {
        $timeOffset = '240';
    }

    $userISTimeZone = 'America/New_York';
}

?>

<script type="text/javascript">
    (function (open) {
        XMLHttpRequest.prototype.open = function (method, url, async, user, pass) {
            var rewrittenUrl = url.replace(/time_zone=[A-Za-z_]*%2F[A-Za-z_]*/g, 'time_zone=<?php echo $userISTimeZone; ?>')
                                  .replace(/time_zone_offset=-?[0-9]*/, 'time_zone_offset=<?php echo $timeOffset; ?>')
            open.call(this, method, rewrittenUrl, async, user, pass)
        }
    })(XMLHttpRequest.prototype.open)
</script>

<!-- Hotjar Tracking Code for https://learn.cleverinvestor.com -->
    <script>
    (function(h,o,t,j,a,r){
        h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
        h._hjSettings={hjid:1145495,hjsv:6};
        a=o.getElementsByTagName('head')[0];
        r=o.createElement('script');r.async=1;
        r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
        a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
</script>

<div style="display:none;">
    <div id="student-name"><?php echo $student_name; ?></div>
    <div id="student-phone"><?php echo $phone; ?></div>
    <div id="student-email"><?php echo $email; ?></div>
</div>

<div class="deal-review">

    <div id="primary" class="site-content">
        <div id="content" role="main">

            <article <?php post_class(); ?>>
                <header class="entry-header">
                    <div class="editEvent"></div>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="editEvent"></div>
                </header>

                <div class="entry-content">
                    <h2><?php if (trim($student_name)) {
                            echo 'Booking for ' . $student_name . ',';
                        } ?> Times shown in <?php echo $userISTimeZone; ?></h2>
                    <?php
                    echo do_shortcode('[bookly-form service_id="1" staff_member_id="' . $staff_id . '" hide="categories,staff_members,date,week_days,time_range"]');
                    ?>

                    <h2>Appointments - times shown America/New_York</h2>

                    <?php
                    $query_string = "SELECT staff_id, start_date, end_date, internal_note FROM $appts as app LEFT JOIN $custAppts as ca ON app.id = ca.appointment_id LEFT JOIN $cust as customers ON customers.id = ca.customer_id WHERE customers.wp_user_id = %d";

                    $query = $wpdb->prepare($query_string, $student_id);
                    $results = $wpdb->get_results($query, 'ARRAY_A');

                    echo '<table class="appointments table">
            <thead>
              <th class="mentor">Mentor</th>
              <th class="time1">Start Time</th>
              <th class="time2">End Time</th>
              <th class="notes">Notes</th>
            </thead>
            <tbody>';
                    if ($results) {
                        foreach ($results as $result) {
                            $staff_query_string = "SELECT wp_user_id, full_name FROM $bookDBStaff WHERE id = %d LIMIT %d";

                            $staff_query = $wpdb->prepare($staff_query_string, $result['staff_id'], 1);
                            $staff = $wpdb->get_results($staff_query, 'ARRAY_A');

                            $profile_link = add_query_arg(array(
                                'user_id' => $staff[0]['wp_user_id'],
                            ), get_permalink(819));

                            echo '<tr>
                <td class="mentor"><a href="' . $profile_link . '">' . $staff[0]['full_name'] . '</a></td>
                <td class="time1">' . date('n/d/y g:i a', strtotime($result['start_date'])) . '</td>
                <td class="time2">' . date('n/d/y g:i a', strtotime($result['end_date'])) . '</td>
                <td class="notes">' . ($result['internal_note'] ? $result['internal_note'] : 'No notes') . '</td>
              </tr>';
                        }
                    } else {
                        echo '<tr colspan="2">This user has no mentor calls yet.</tr>';
                    }
                    echo '</tbody>
          </table>';
                    ?>
                </div>
