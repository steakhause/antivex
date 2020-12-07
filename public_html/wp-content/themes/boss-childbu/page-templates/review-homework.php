<?php
/**
 * Template Name: Review Homework Page Template
 *
 * Description: Use this page template for review homework page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

date_default_timezone_set("America/New_York");
?>

<div class="assign-homework">

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
                        if ($_GET['user_assignment']) {
                            $user_assignment_id = sanitize_text_field($_GET['user_assignment']);
                            $assigned_user_id = ($_GET['user_id'] ? sanitize_text_field($_GET['user_id']) : 0);

                            $assignment = get_assignments_for_review(get_current_user_id(), $user_assignment_id, $assigned_user_id);

                            echo '<h2>'.$assignment['title'].'</h2>
							<p><a href="" class="btn">Edit Homework</a></p>
							<p>'.apply_filters('the_content', stripslashes($assignment['instructions'])).'</p>';

                            if (!current_user_can('administrator') || !current_user_can('fulfillment_role')) {
                                echo '<h3>Assignments and Responses</h3>';

                                if (count($assignment['answers']) > 0) {
                                    foreach ($assignment['answers'] as $answer) {
                                        $userMentorId = get_user_meta($answer['user_id'], '_assignedMentor', true);
                                        if (current_user_can('fulfillment_role') && current_user_can('administrator') || $userMentorId == get_current_user_id()) {
                                            echo '<div class="assignmentWrap">';
                                            echo '<p><strong>'.get_user_meta($answer['user_id'], 'first_name', true)." ".get_user_meta($answer['user_id'], 'last_name', true).' - Assigned on '.date('F j, Y g:i a T', strtotime($answer['date_assigned'])).'</strong></p>';
                                            if ($answer['date_posted']) {
                                                echo '<p class="italic">Answered on '.date('F j, Y g:i a T', strtotime($answer['date_posted'])).':</p>';
                                                echo '<p>'.$answer['response'].'</p>';
                                            } else {
                                                echo '<p>Not completed yet.';
                                            }
                                            echo '</div>';
                                        }
                                    }
                                } else {
                                    echo 'There are no answers to this assignment.';
                                }
                            }
                        } else {
                            $assignments = get_assignments_for_review(get_current_user_id());
                            if ($assignments) {
                                foreach ($assignments as $assignment) {
                                    echo '<div class="assignmentWrap">';
                                    $edit_link = add_query_arg(array(
                                        'homework_id' => $assignment['id']
                                    ), get_permalink(1185));

                                    echo '<h2>'.$assignment['title'];
                                    if (current_user_can('fulfillment_role') || current_user_can('administrator')) {
                                        echo ' - <a href="'.$edit_link.'">Edit</a>';
                                    } elseif (current_user_can('mentor_role') && $assignment['mentor_id'] == get_current_user_id()) {
                                        echo ' - <a href="'.$edit_link.'">Edit</a>';
                                    }
                                    echo '</h2>';
                                    echo apply_filters('the_content', stripslashes($assignment['instructions']));
                                    if (!current_user_can('administrator') && !current_user_can('fulfillment_role')) {
                                        echo '<h3>Assignments and Responses</h3>';
                                        if ($assignment['answers']) {
                                            foreach ($assignment['answers'] as $answer) {
                                                $userMentorId = get_user_meta($answer['user_id'], '_assignedMentor', true);
                                                if (current_user_can('fulfillment_role') || current_user_can('administrator') || $userMentorId == get_current_user_id()) {
                                                    echo '<div class="singleAssignmentWrap">';
                                                    echo '<p><strong>'.get_user_meta($answer['user_id'], 'first_name', true)." ".get_user_meta($answer['user_id'], 'last_name', true).' - Assigned on '.date('F j, Y g:i a T', strtotime($answer['date_assigned'])).'</strong></p>';
                                                    if ($answer['date_posted'] != "0000-00-00 00:00:00") {
                                                        echo '<p class="italic">Answered on '.date('F j, Y g:i a T', strtotime($answer['date_posted'])).':</p>';
                                                        echo '<p>'.$answer['response'].'</p>';
                                                    } else {
                                                        echo '<p>Not completed yet.';
                                                    }
                                                    echo '</div>';
                                                }
                                            }
                                        } else {
                                            echo '<p>There are no answers for this assignment.</p>';
                                        }
                                    }
                                    echo '</div>';
                                }
                            }
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
