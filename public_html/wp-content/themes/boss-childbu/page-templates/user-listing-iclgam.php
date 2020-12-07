<?php
/**
 * Template Name: User ICLGAM Listing Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
date_default_timezone_set("America/New_York");
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

?>

    <div id="user-settings" class="user-settings">
      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header"  <?php if($banner_image) { ?>  style="background-size: cover; background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
                  <!-- page title -->
                  <div class="editEvent"></div>
                  <h1 class="entry-title"><?php the_title(); ?></h1>
                  <div class="editEvent"></div>
              </header>

              <div class="entry-content">
                <?php
                  // Get page content if there is any to allow for an editable description.
          				if ( have_posts() ) {
          					while ( have_posts() ) {
          						the_post();
          						the_content();
          					}
          				}
                ?>

                <?php
                $mentorList = get_users(array('role'=>'mentor_role'));
                $studentList = get_users(array('role'=>'mentoring_student_role'));

function buildStudentHTML($studentData){
  $conId = memb_getContactIdByUserId($studentData->ID);
    $tentionMrkp = (memb_hasAnyTags("6998", $conId))?'<i class="fas fa-flag" style="color:#ff0000;margin-right:5px;"></i>':'';
    $tentionMrkp .= (memb_hasAnyTags("7000", $conId))?'<i class="fas fa-flag" style="color:#ffff00;margin-right:5px;"></i>':'';
    $studentHTML = '        <li><span class="retention_status">'.$tentionMrkp.'</span>'.get_avatar( $studentData->ID, 24 )."\n          "
                 . '<a href="'.get_permalink(819).'?user_id='.$studentData->ID.'">'.$studentData->display_name."</a>\n          "
                 . '<span><i> Last logged in: ';
    if(! empty(get_user_meta($studentData->ID, 'last_login_time', true)) )
        $studentHTML .= date("m/d/Y H:i:s", get_user_meta($studentData->ID, 'last_login_time', true));
    else
        $studentHTML .= "Never";
    $studentHTML .= "</i></span></li>\n";
	return $studentHTML;
}

if ( $mentorList ) {

    if ( current_user_can("fulfillment_role") || current_user_can("administrator") ) {
        echo '<h4>Select a mentor from the list:</h4>'."\n"
           . '<select id="user-listing">'."\n"
           . '    <option value="0" selected="selected">All Mentors</option>'."\n";
        foreach( $mentorList as $mentors ){
            echo '    <option value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</option>'."\n";
        }
        echo '</select>'."\n";
    }

foreach( $mentorList as $mentorKeys => $mentors ){
    if( current_user_can("fulfillment_role") || current_user_can("administrator") || $mentors->data->ID == get_current_user_id() ){
        echo '<div class="displayMentorsAndStudents" data-mentor="'.$mentors->data->ID.'">'."\n";
        if( current_user_can("fulfillment_role") || current_user_can("administrator") ){
            echo '    <div class="displayMentor">'."\n        ".'<strong>Mentor:</strong><br>'."\n        ".''.get_avatar( $mentors->data->ID, 24 )."\n        ".'<a href="'.get_permalink(819).'?user_id='.$mentors->data->ID.'"> '.$mentors->data->display_name.' </a>'."\n    ".'</div>'."\n";
        }
        if( $studentList ){ // <-- Isn't this always true by this point? E.N.
		$activeStudentsHTML = $studentHoldsHTML = $programGraduatesHTML = $unassignedStudentsHTML = $iclgamStudentsHTML =""; // reset HTML output
            // Organize HTML for each group of students (Active, On Hold, Completed, Unassigned) beforehand.
            foreach( $studentList as $studentKeys => $students ){
                if (get_user_meta($students->data->ID,'_assignedMentor',true) == $mentors->data->ID || memb_hasAnyTags("11074",get_user_meta( $students->data->ID, 'infusionsoft_user_id', true ))) {
                    $contact_id = get_user_meta( $students->data->ID, 'infusionsoft_user_id', true );
                    if(memb_hasAnyTags("11074",$contact_id)){            // Student has InfusionSoft Tag: [Access: COURSE(Unlockin OLC) -> ICLGAM: Inner Circle Lead Gen Auto Mastery]
                        $iclgamStudentsHTML .= buildStudentHTML($students->data);
                    }else if(memb_hasAnyTags("4798",$contact_id)){            // Student has InfusionSoft Tag: [Mentoring -> Completed Mentoring Program]
                        $programGraduatesHTML .= buildStudentHTML($students->data);
                    }else if(memb_hasAnyTags("4311,4219",$contact_id)){ // Student has InfuSoft Tag: [On Pause - Do Not Disturb] or [Pause Mentoring]
                        $studentHoldsHTML .= buildStudentHTML($students->data);
                    }else if(memb_hasAnyTags("3555",$contact_id)){      // Student has InfuSoft Tag: [Tracking -> Active Mentoring Student]
                        $activeStudentsHTML .= buildStudentHTML($students->data);
                    }else{                                              // Student does NOT have any valid InfuSoft sorting tags.
                        $unassignedStudentsHTML .= buildStudentHTML($students->data);
                    }
                } // student-mentor match check
            } // foreach student
			// Output the organized HTML
            echo '    <ul class="displayMentoringStudents" data-mentor="'.$mentors->data->ID.'"><strong>Mentoring Students:</strong>'."\n";
			if( empty($activeStudentsHTML) && empty($studentHoldsHTML) && empty($programGraduatesHTML) && empty($unassignedStudentsHTML) && empty($iclgamStudentsHTML))
                echo '<i>No Students Assigned</i>';
			else{
                if(! empty($activeStudentsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Active Students:</stron></li>\n" . $activeStudentsHTML;
                if(! empty($studentHoldsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">On Hold Students:</stron></li>\n" . $studentHoldsHTML;
                if(! empty($programGraduatesHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Completed Students:</stron></li>\n" . $programGraduatesHTML;
                if(! empty($unassignedStudentsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Active Students:</stron></li>\n" . $unassignedStudentsHTML;
                if(! empty($iclgamStudentsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Lead Gen & Automation Mastery Students:</stron></li>\n" . $iclgamStudentsHTML;
			}
            echo '    </ul>'."\n";
        } // ( $studentList )
        echo '</div>'."\n"; // Closes a mentor div
    } // role check
} // foreach mentor
                }
                ?>
              </div>

              <footer class="entry-footer">
                  <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
              </footer>
          </article>
          <?php comments_template( '', true ); ?>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>



 <?php get_footer(); ?>
