<?php
/**
 * Template Name: User Listing Page Template
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

 if (array_key_exists('user_id', $_GET) &&  $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }


 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

	$banner_image = get_user_meta( $user_id, 'banner_image', true );
/* Banner images code end */
$graduateIds = array();
$holdIds = array();
$activeIds = array();
$unassignedIds = array();

?>
<script type="text/javascript">
jQuery(document).ready(function(){
  jQuery('.userListMentorSortTab').show();
  jQuery('.userListAllStudentsTab').hide();
  jQuery('.usersTabSelector span').on('click', function(){
    if(jQuery(this).hasClass('userMentorSelector')){
      jQuery('.userListAllStudentsTab').fadeOut();
      jQuery('.userListMentorSortTab').fadeIn();
    }
    if(jQuery(this).hasClass('userAllSelector')){
      jQuery('.userListMentorSortTab').fadeOut();
      jQuery('.userListAllStudentsTab').fadeIn();
    }
  });
  jQuery('ul.displayAllMentoringStudentsTabbed li').each(function(){
    if(jQuery(this).data("student_status") != "active"){
      jQuery(this).hide();
    }
  });
  jQuery('select#userStatusSelector').on('change', function(){
    console.log(jQuery(this).val());
    var statSelectVal = jQuery(this).val()
    jQuery('ul.displayAllMentoringStudentsTabbed li').each(function(){
      if(jQuery(this).data("student_status") != statSelectVal){
        jQuery(this).hide();
      } else {
        jQuery(this).show();
      }
    });
  });
})
</script>
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
                <style>
                .usersTabSelector span {
                  border-top: 1px solid gray;
                  border-right: 1px solid gray;
                  border-left: 1px solid gray;
                  border-top-left-radius: 4px;
                  border-top-right-radius: 4px;
                  cursor: pointer;
                  padding:10px;
                }
                </style>
                <div class="usersTabSelector"><span class="userMentorSelector">Users By Mentor</span><span class="userAllSelector">Users By Status</span></div>
                <?php
                $mentorList = get_users(array('role'=>'mentor_role'));
                $studentList = get_users(array('role'=>'mentoring_student_role', 'orderby' => 'display_name'));

function buildStudentHTML($studentData, $studentTypeAttr = ""){
    $conId = memb_getContactIdByUserId($studentData->ID);
    $tentionMrkp = (memb_hasAnyTags("6998", $conId))?'<i class="fas fa-flag" style="color:#ff0000;margin-right:5px;"></i>':'';
    $tentionMrkp .= (memb_hasAnyTags("7000", $conId))?'<i class="fas fa-flag" style="color:#ffff00;margin-right:5px;"></i>':'';
    $studentHTML = '        <li '.$studentTypeAttr.'>'.get_avatar( $studentData->ID, 24 )."\n          "
                 . '<a href="'.get_permalink(819).'?user_id='.$studentData->ID.'">'.$studentData->display_name."</a>\n          "
                 . '<span class="retention_status">'.$tentionMrkp.'</span><span><i> Last logged in: ';
    if(! empty(get_user_meta($studentData->ID, 'last_login_time', true)) )
        $studentHTML .= date("m/d/Y H:i:s", get_user_meta($studentData->ID, 'last_login_time', true));
    else
        $studentHTML .= "Never";
    $studentHTML .= "</i></span></li>\n";
	return $studentHTML;
}

if ( $mentorList ) {
  echo '<div class="userListMentorSortTab">';
    if ( current_user_can("fulfillment_role") || current_user_can("administrator") ) {
        echo '<h4>Select a mentor from the list:</h4>'."\n"
           . '<select id="user-listing">'."\n"
           . '    <option value="0" selected="selected">All Mentors</option>'."\n";
        foreach( $mentorList as $mentors ){
            echo '    <option value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</option>'."\n";
        }
        echo '</select>'."\n";
    }


    if ( current_user_can("fulfillment_role") || current_user_can("administrator") || current_user_can("mentor_role") ) {
        echo'    <ul class="displayMentoringStudents"><strong>Inner Circle Mastery Students:</strong>'."\n";
        //ICLGAM Students
        $iclgamStudentsHTML ="";
        foreach( $studentList as $studentKeys => $students ){
            if(memb_hasAnyTags("12168",get_user_meta( $students->data->ID, 'infusionsoft_user_id', true ))){
               echo  buildStudentHTML($students->data);
            }
        }
        echo '    </ul>'."\n";
    }

foreach( $mentorList as $mentorKeys => $mentors ){
    if( current_user_can("fulfillment_role") || current_user_can("administrator") || $mentors->data->ID == get_current_user_id() ){
        echo '<div class="displayMentorsAndStudents" data-mentor="'.$mentors->data->ID.'">'."\n";
        if( current_user_can("fulfillment_role") || current_user_can("administrator") ){
            echo '    <div class="displayMentor">'."\n        ".'<strong>Mentor:</strong><br>'."\n        ".''.get_avatar( $mentors->data->ID, 24 )."\n        ".'<a href="'.get_permalink(819).'?user_id='.$mentors->data->ID.'"> '.$mentors->data->display_name.' </a>'."\n    ".'</div>'."\n";
        }
        if( $studentList ){ // <-- Isn't this always true by this point? E.N.
		$activeStudentsHTML = $studentHoldsHTML = $programGraduatesHTML = $unassignedStudentsHTML = ""; // reset HTML output
            // Organize HTML for each group of students (Active, On Hold, Completed, Unassigned) beforehand.
            //echo '<pre>'.var_export($studentList, 1).'</pre>';
            foreach( $studentList as $studentKeys => $students ){
                if (get_user_meta($students->data->ID,'_assignedMentor',true) == $mentors->data->ID) {
                    $contact_id = get_user_meta( $students->data->ID, 'infusionsoft_user_id', true );
                    if(memb_hasAnyTags("4798",$contact_id)){            // Student has InfusionSoft Tag: [Mentoring -> Completed Mentoring Program]
                        $programGraduatesHTML .= buildStudentHTML($students->data);
                        array_push($graduateIds, $students->data->ID);
                    }else if(memb_hasAnyTags("4311,4219",$contact_id)){ // Student has InfuSoft Tag: [On Pause - Do Not Disturb] or [Pause Mentoring]
                        $studentHoldsHTML .= buildStudentHTML($students->data);
                        array_push($holdIds, $students->data->ID);
                    }else if(memb_hasAnyTags("3555",$contact_id)){      // Student has InfuSoft Tag: [Tracking -> Active Mentoring Student]
                        $activeStudentsHTML .= buildStudentHTML($students->data);
                        array_push($activeIds, $students->data->ID);
                    }else{                                              // Student does NOT have any valid InfuSoft sorting tags.
                        $unassignedStudentsHTML .= buildStudentHTML($students->data);
                        array_push($unassignedIds, $students->data->ID);
                    }
                } // student-mentor match check
            } // foreach student
			// Output the organized HTML
            echo '    <ul class="displayMentoringStudents" data-mentor="'.$mentors->data->ID.'"><strong>Mentoring Students:</strong>'."\n";
			if( empty($activeStudentsHTML) && empty($studentHoldsHTML) && empty($programGraduatesHTML) && empty($unassignedStudentsHTML)){
                echo '<i>None</i>';
			} else {
                if(! empty($activeStudentsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Active Students:</strong></li>\n" . $activeStudentsHTML;
                if(! empty($studentHoldsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">On Hold Students:</strong></li>\n" . $studentHoldsHTML;
                if(! empty($programGraduatesHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Completed Students:</strong></li>\n" . $programGraduatesHTML;
                if(! empty($unassignedStudentsHTML) )
                    echo "        <li><strong style=\"font-size:14px\">Unassigned Students:</strong></li>\n" . $unassignedStudentsHTML;
			}
            echo '    </ul>'."\n";
        } // ( $studentList )
        echo '</div>'."\n"; // Closes a mentor div
    } // role check
} // foreach mentor
echo '</div>';
} // If mentorList
if($studentList){ ?>
  <script type="text/javascript">
    jQuery(document).ready(function(){

    });
  </script>
  <?php
  echo '<div class="userListAllStudentsTab">';
  echo '<h4>Select a student status from the list:</h4>';
  echo '<select id="userStatusSelector" name="userStatusSelector"><option value="active" selected>Active</option><option value="graduate">Graduate</option><option value="hold">Hold</option><option value="unassigned">Unassigned</option></select>';
  echo '<ul class="displayAllMentoringStudentsTabbed">';
  foreach($studentList as $student_key => $student_value){
    if(in_array($student_value->data->ID, $graduateIds)){
      echo buildStudentHTML($student_value->data, 'data-student_status="graduate"');
    }
    if(in_array($student_value->data->ID, $holdIds)){
      echo buildStudentHTML($student_value->data, 'data-student_status="hold"');
    }
    if(in_array($student_value->data->ID, $activeIds)){
      echo buildStudentHTML($student_value->data, 'data-student_status="active"');
    }
    if(in_array($student_value->data->ID, $unassignedIds)){
      echo buildStudentHTML($student_value->data, 'data-student_status="unassigned"');
    }
  }
  echo '</ul></div>';
} // If studentList
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
