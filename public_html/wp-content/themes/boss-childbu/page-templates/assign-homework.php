<?php
/**
 * Template Name: Assign Homework Page Template
 *
 * Description: Use this page template for assign homework page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

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
            <form method="post" action="<?php echo site_url().'/index.php?assignments=1'; ?>">
            <h2>Assign Homework</h2>
            <h4>Assignment</h4>
            <select id="choose-assignment" name="assignment">
              <option value="">Select an Assignment</option>
              <?php
                $assignments = get_mentor_assignments( get_current_user_id() );

                if ( $assignments ){
                  foreach( $assignments as $assignment ){
                    echo '<option value="'.$assignment['id'].'">'.stripslashes( $assignment['title'] ).'</option>';
                  }
                }
               ?>
             </select>

						 <h4>Assignment Details</h4>
						 <div class="assignment-details">
							 Select an assignment above to see the details.
						 </div>

               <h4>Assign to Students</h4>
               <?php
                 if ( current_user_can("fulfillment_role") ) {
                   echo '<input type="checkbox" name="allCheck" id="allCheck" class="allCheck" value=""><span class="selectAllGroups">Select All</span><br />';
                 }
                   $mentorList = get_users(array('role'=>'mentor_role'));
                   $studentList = get_users(array('role'=>'mentoring_student_role'));

                 if ( $mentorList ) {
                   foreach ( $mentorList as $mentorKeys => $mentors ) {
                     if ( current_user_can("fulfillment_role") || current_user_can('administrator') || $mentors->data->ID == get_current_user_id() ) {
                       echo '<div class="displayAllMentorsAndStudents">';
                         if ( current_user_can("fulfillment_role") || current_user_can('administrator') ) {
                           echo '<div class="allCheckContainer"><input type="checkbox" class="mentorCheck eachCheck" name="assign[]" value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</div>';
                            // echo '<div class="allCheckContainer">'.$mentors->data->display_name.'</div>';
                         }
                         if ( $studentList ) {
                           echo '<ul class="displayAllMentoringStudents">';
                           foreach ( $studentList as $studentKeys => $students ) {
                             if (get_user_meta($students->data->ID)['_assignedMentor'][0] == $mentors->data->ID) {
                               echo '<li><input type="checkbox" class="studentCheck eachCheck" name="assign[]" value="'.$students->data->ID.'">'.$students->data->display_name.'</li>';
                             }
                           }
                           echo '</ul>';
                         }
                         echo '<input type="checkbox" name="groupCheck" class="groupCheck" value=""><i>Select All in this Group</i><br />';
                       echo '</div>';
                     }
                   }
                 }
               ?>

               <input type="hidden" name="action" value="assign">

               <div class="form-row">
                 <input type="submit" value="Assign Homework">
               </div>

             </form>

          </div>

      </article>
    </div>
  </div>
</div>

<?php
get_footer();

 ?>
