<?php
/**
 * Template Name: Student Homework Page Template
 *
 * Description: Use this page template for student homework page.
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

date_default_timezone_set("America/New_York");
if ($_GET['heather']) {
    $postId = $post->ID;
    echo $postId;
}

if ($_GET['student']) {
    $viewing_user = sanitize_text_field($_GET['student']);
    $view_all = true;
} else {
    $viewing_user = get_current_user_id();
    $view_all = true;
}

?>

<style>
.student-homework textarea {
	width: 20%;
  min-width: 400px;
	min-height: 150px;
	border: 2px solid #4dcadd;
	border-radius: 5px;
	font-size: 15px;
	padding: 10px;
	color: #4dcadd;
	box-sizing: border-box;
	background-color: #fff;
}

.answer{
  font-style: italic;
}

.student-homework input[type="submit"] {
  margin-top: 10px;
}

.student-homework .entry-content p {
  width: 50%;
  min-width: 400px;
}
</style>

<div class="student-homework">

    <div id="primary" class="site-content">
        <div id="content" role="main">

            <article <?php post_class(); ?>>
                <header class="entry-header" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
                    <div class="editEvent"></div>
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    <div class="editEvent"></div>
                </header>

                <div class="entry-content">
                <?php
                $assignments = get_student_assignments($viewing_user);

                $incompleteAssignments = get_student_incomplete_assignments($viewing_user);

                if($incompleteAssignments) {
                    echo '<h2 class="assignmentHeader">Incomplete Assignments</h2>';
                    foreach($incompleteAssignments as $incomplete) {
                        $date_assigned = $incomplete['date_assigned'];
                        $timestamp = strtotime($date_assigned);
                        $new_date_assigned_format = date('F j, Y g:i a T', $timestamp);

                        $date_completed = $incomplete['date_posted'];
                        $timestamp_completed = strtotime($date_completed);
                        $new_date_completed_format = date('F j, Y g:i a T', $timestamp_completed);

                        echo '<article>';
                        echo '<h3>'. $incomplete['title'] . ' &mdash; Assigned On: ' .$new_date_assigned_format . '</h3>
                <p>'.apply_filters('the_content', stripslashes($incomplete['content'])).'</p>';

                        echo '<p><strong>Optional Comments:</strong></p>';
                        echo '<form method="post" action="'.site_url().'/index.php?assignments=1"><div class="form-row"><textarea name="answer" placeholder="Completion Notes (Optional)"></textarea></div><input type="hidden" name="action" value="answer"><input type="hidden" name="user_assignment_id" value="'.$incomplete['user_assignment_id'].'"><input type="submit" value="Assignment Completed"></form>';
                        echo '<div class="borderSeparator"></div>';
                        echo '</article>';
                    }
                }

                if ($assignments) {
                    echo '<h2 class="assignmentHeader">Completed Assignments</h2>';
                    foreach ($assignments as $assignment) {
                        $date_assigned = $assignment['date_assigned'];
                        $timestamp = strtotime($date_assigned);
                        $new_date_assigned_format = date('F j, Y g:i a T', $timestamp);

                        $date_completed = $assignment['date_posted'];
                        $timestamp_completed = strtotime($date_completed);
                        $new_date_completed_format = date('F j, Y g:i a T', $timestamp_completed);

                        echo '<article>';
                        echo '<h3>'. $assignment['title'] . ' &mdash; Assigned On: ' .$new_date_assigned_format . '</h3>
                <p>'.apply_filters('the_content', stripslashes($assignment['content'])).'</p>';
                        echo '<p class="date-completed"><strong>Date Completed:  </strong>' . $new_date_completed_format .'</p>';
                        echo '<p><strong>Optional Comments:</strong></p>';
                        echo '<p class="answer">'.$assignment['response'].'</p>';
                        echo '<div class="borderSeparator"></div>';
                        echo '</article>';
                    }
                } else {
                    echo '<p>You do not have any homework assigned to you yet.</p>';
                }
                ?>

                </div>

            </article>
        </div>
    </div>
</div>

<?php
get_footer();

 ?>
