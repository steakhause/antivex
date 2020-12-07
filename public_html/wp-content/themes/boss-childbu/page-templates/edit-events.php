<?php
/**
 * Template Name: Edit Events Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
get_header(); ?>

<?php
  global $i2sdk;
  $app = $i2sdk->isdk;

  $user = wp_get_current_user();
  $event_id = sanitize_text_field( $_GET['eventId'] );
  $is_clone = sanitize_text_field( $_GET['clone'] );

  $event_meta = get_post_meta( $event_id );

  $eventTags = get_post_meta($event_id, '_eventMentorByTag', true);
?>

<div class="edit-events">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php
				if ( have_posts() ) {
					while ( have_posts() ) {
						the_post();
						the_content();
					}
				}
			?>

			<form id="eventBuilder" class="eventBuilder" action="<?php echo get_site_url(); ?>/wp-content/themes/boss-child/edit-redirect.php" method="post">
				<input type="hidden" name="postId" value="<?php echo ( $is_clone ? '' : $event_id ); ?>">
				<ul class="editWindow">
          <?php
            echo '<li class="eventTitle"><label>Enter Event Name</label><input type="text" name="eventTitle" placeholder="Event Name"'.( $event_meta['eventTitle'][0] ? ' value="'.$event_meta['eventTitle'][0].'"' : '' ).' /></li>';

            echo '<li class="EventDate"><label>Enter Event Date</label><input type="text" name="eventDate" class="datepicker" placeholder="Event Date/Time"'.( $event_meta['eventDate'][0] && !$is_clone ? ' value="'.$event_meta['eventDate'][0].'"' : '' ).' /></li>';

            echo '<li class="eventLocation"><label>Enter Event Location</label><input type="text" name="eventLocation" placeholder="Event Location"'.( $event_meta['eventLocation'][0] ? ' value="'.$event_meta['eventLocation'][0].'"' : '' ).' /></li>';

            echo '<li class="eventLinkYes"><label>Should the event location be a link?</label><input type="checkbox" name="eventLinkYes" id="showHideLocationLink" value="1"'.( $event_meta['eventLocationLink'][0] ? ' checked' : '' ).' /></li>';

            echo '<li class="eventLocationLink'.( $event_meta['eventLocationLink'][0] ? ' active' : '' ).'"><label>Event Location Link (http://url.com)</label><input type="text" name="eventLocationLink" paceholder="Event Link"'.( $event_meta['eventLocationLink'][0] ? ' value="'.$event_meta['eventLocationLink'][0].'"' : '' ).' /></li>';

					  echo '<li class="eventTime">Enter Event Time<br />
						  <select class="eventHour" name="eventHour">';

                if( $event_meta['eventHour'][0] ){
                  echo '<option value='.$event_meta['eventHour'][0].'>'.$event_meta['eventHour'][0].'</option>';
                }else{
                  echo '<option value="None" selected>Hour</option>';
                } ?>
  							<option value="01">01</option>
  							<option value="02">02</option>
  							<option value="03">03</option>
  							<option value="04">04</option>
  							<option value="05">05</option>
  							<option value="06">06</option>
  							<option value="07">07</option>
  							<option value="08">08</option>
  							<option value="09">09</option>
  							<option value="10">10</option>
  							<option value="11">11</option>
  							<option value="12">12</option>
  					  </select>
  						<select class="eventMinute" name="eventMinute">
                <?php
                if( $event_meta['eventMinute'][0] ){
                  echo '<option value='.$event_meta['eventMinute'][0].'>'.$event_meta['eventMinute'][0].'</option>';
                }else{
                  echo '<option value="None" selected>Minute</option>';
                } ?>
  							<option value="00">00</option>
  							<option value="15">15</option>
  							<option value="30">30</option>
  							<option value="45">45</option>
  					  </select>
  						<select class="eventAmPm" name="eventAmPm">
                <?php
                if( $event_meta['eventAmPm'][0] ){
                  echo '<option value='.$event_meta['eventAmPm'][0].'>'.$event_meta['eventAmPm'][0].'</option>';
                }else{
                  echo '<option value="None" selected>AM / PM</option>';
                } ?>
  							<option value="AM">AM</option>
  							<option value="PM">PM</option>
  						</select>
  						<select class="eventTimeZone" name="eventTimeZone">
                <?php
                if( $event_meta['eventTimeZone'][0] ){
                  echo '<option value='.$event_meta['eventTimeZone'][0].'>'.$event_meta['eventTimeZone'][0].'</option>';
                }else{
                  echo '<option value="None" selected>Time Zone</option>';
                } ?>
  							<option value="EST">EST</option>
  							<option value="CST">CST</option>
                <option value="MST">MST</option>
  							<option value="MST AZ">MST AZ</option>
  							<option value="PST">PST</option>
  					  </select>
					</li>
          <?php
          echo '<li class="eventDescription"><label>Enter Event Description</label><textarea name="eventDescription" placeholder="Event Description">'.get_post_field('post_content', $event_id).'</textarea></li>';
           ?>

          <!-- <h4>Choose a Mentor List:</h4> -->
          <div class="listSelector">
            <label>Choose a List</label>
            <input type="hidden" name="userId" value="<?php echo get_current_user_id(); ?>">
            <!-- <input type="radio" id="listByTag" name="mentorList" value="0" checked>List by Tag<br />
            <input type="radio" id="listByName" name="mentorList" value="1">List by Mentor<br /><br /> -->
            <!-- <input type="radio" id="listByTag" name="mentorList" value="List by Tag" checked>List by Tag<br />
            <input type="radio" id="listByName" name="mentorList" value="List by Name">List by Mentor<br /><br /> -->
          </div>
          
          <?php
          // Get an array of mentors with their Infusionsoft ID as the keys
          $mentor_list = get_infusionsoft_tag_ids_by_category_id(126, $app);

          echo '<div id="mentorByTag" class="mentorByTag form-row">
            <div id="dropDownContainer">
              <select id="eventMentor" class="eventMentorTag eventMentor form-input" name="eventMentorByTagbak">';
                echo '<option value="None" selected>Select a Tag</option>';
               foreach($mentor_list as $tag) {
                   echo '<option value="' . $tag['Id'] . '">' . $tag['Id'] . ' &mdash; ' . $tag['GroupName'] . '</option>';
               }
              echo '</select>';
              echo '</select><i class="fa fa-plus-circle membership-span" aria-hidden="true"></i><input type="hidden" class="membVals" name="eventMentorByTag" value="' . $eventTags . '">
            </div>';

            $tagList = explode(',', $eventTags);
            $tagHTML = '';
            foreach($tagList as $tag) {
                if($tag) {
                    $tagName = do_shortcode('[memb_tag_name tagids="' . $tag . '" ]');
                    $tagHTML .= '<li data-tagid="' . $tag . '">' . $tag . ' &mdash; ' . $tagName . '<i class="fa fa-times-circle removeMemb"></i></li>';
                }
            }

            echo '<div id="tagContainer"><p>Tags:</p><ul>' . $tagHTML . '</ul></div>';

            // $eventMentoringStudents = unserialize(get_post_meta( $event_id, '_eventMentoringStudents', true ));
            //
            // if ( $eventMentoringStudents ){
            //   foreach( $eventMentoringStudents as $key => $value ){
            //     $currentStudents['eventMentoringStudents[' . $key . ']'] = $value;
            //   }
            // }

          echo '</div>';
          ?>
          <input type="submit" value="SUBMIT" />
          <div class="form-row">
            <!-- <h4 for="form-checkbox">Select Recipients</h4><br /> -->
            <?php
              if ( current_user_can("fulfillment_role") || current_user_can('administrator')) {
                echo '<label>Select Recipients</label><br />
                <input type="checkbox" name="allCheck" id="allCheck" class="allCheck" value=""><span class="selectAllGroups">Select All</span><br />';
              }
                $mentorList = get_users(array('role'=>'mentor_role'));
                $studentList = get_users(array('role'=>'mentoring_student_role'));

                $assignedUsers = get_post_meta($event_id, '_eventMentorByName', true);

              if ( $mentorList ) {
                foreach ( $mentorList as $mentorKeys => $mentors ) {
                  if ( current_user_can("fulfillment_role") || current_user_can('administrator') || $mentors->data->ID == get_current_user_id() ) {
                    echo '<div class="displayAllMentorsAndStudents">';
                      if ( current_user_can("fulfillment_role") || current_user_can('administrator')) {
                          $checked = '';
                          if(array_key_exists($mentors->data->ID, $assignedUsers) ) {
                              $checked = 'checked="checked"';
                               echo '<div class="allCheckContainer"><input type="checkbox" ' . $checked . ' class="mentorCheck eachCheck" name="eventMentorByName['.$mentors->data->ID.'][]" value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</div>';
                          } else {
                              echo '<div class="allCheckContainer"><input type="checkbox" class="mentorCheck eachCheck" name="eventMentorByName['.$mentors->data->ID.'][]" value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</div>';
                          }

                      }
                      if ( $studentList ) {
                        echo '<ul class="displayAllMentoringStudents">';
                        foreach ( $studentList as $studentKeys => $students ) {
                          if (get_user_meta($students->data->ID)['_assignedMentor'][0] == $mentors->data->ID) {
                              foreach($assignedUsers as $user) {
                                  $checked = '';
                                  if(in_array( $students->data->ID, $user)) {
                                      $checked = 'checked="checked"';
                                      break;
                                  }
                              }
                            echo '<li><input type="checkbox" ' . $checked . ' class="studentCheck eachCheck" name="eventMentorByName['.$mentors->data->ID.'][]" value="'.$students->data->ID.'">'.$students->data->display_name.'</li>';
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
        </div>
          <?php
        //   $mentors = get_users(array('role' => 'mentor_role'));

        //   echo '<div id="mentorByName" class="mentorByName" style="display:none">
        //     <select id="eventMentorName" class="eventMentorName eventMentor" name="eventMentorByName">
        //       <option value="None" selected>Select a Mentor</option>';
        //       if ( $mentors ){
        //         foreach ( $mentors as $mentor ) {
        //           echo '<option value="'.$mentor->ID.'">'.$mentor->display_name.'</option>';
        //         }
        //       }
        //     echo '</select><br /><br />';
          //
        //   echo '</div>';

    			// $returnFields = array('Id');
    			// $querys = array('GroupCategoryId' => 126); // Tag Category: Events
    			// $tags = $app->dsQuery("ContactGroup",100,0,$querys,$returnFields);

        //   if ( $tags ){
        //     foreach ( $tags as $tag ) {
        // 			$returnField = array('ContactId', 'Contact.FirstName', 'Contact.LastName');
        // 			$query = array('GroupId' => $tag['Id']);
        // 			$students = $app->dsQuery("ContactGroupAssign",100,0,$query,$returnField);
          //
        //       if ( $students ){
        //         foreach ( $students as $student ){
        //           $args = array(
        //   				  'meta_key'     => 'infusionsoft_user_id',
        //   				  'meta_value'   => $student['ContactId'],
        //   				 );
          //
        //   				 $wpId = new WP_User_Query( $args );
        //   				 if ( ! empty( $wpId->results ) ) {
        //   				 foreach ( $wpId->results as $user ) {
        //   					   $userId = $user->ID;
        //   					 }
        //   				}
        //   				$studentByTag = array(
        //   					'Contact.LastName'	=> $student['Contact.LastName'],
        //   					'Contact.FirstName'	=> $student['Contact.FirstName'],
        //   					'ContactId'					=> $student['ContactId'],
        //   					'WordpressId'				=> $userId
        //   				);
        //   				$contacts[] = $studentByTag;
        //         }
        //       }
     //  			}
        //   }

          echo '<div class="mentoringStudents">';
        //   echo buld_student_list( $contacts, $event_id ).'<br /><br />';
        //   $authors = get_users(array('role' => 'mentor_role'));
        //   foreach ($authors as $keys => $values) {
        //       $mentorId = $values->ID;
        //       $mentorName = $values->display_name;
        //   }
          echo '</div>';
          ?>

          <!-- <div class="eventTable" id="eventTable"><?=show_registered_students($event_id); ?></div><br /><br /> -->

          <input type="hidden" name="eventId" value="<?php echo ( $is_clone ? '' : $event_id ); ?>">
          <input type="hidden" name="eventAttendees" value="<?=attendee_list($event_id); ?>">
          <input type="hidden" name="clone" value="<? echo ( $is_clone ? 1 : 0 ); ?>">
          <input type="submit" value="SUBMIT" /><br /><br />

        </ul>
	    </form>

<?php
// echo '<div class="displayAllMentorsAndStudents">';
//
// $mentorByName = unserialize(get_post_meta($event_id, '_eventMentorsByName', true));
// foreach ( $mentorByName as $key => $mentors ) {
//     echo '<div class="displayEachMentorAndStudents">
//     <p class="allClear"><strong>Attending Mentor:</strong> '.get_user_meta($key)['nickname'][0].'</p>';
//     foreach ($mentors as $studentList) {
//       echo '<ul><strong>Mentoring Students:</strong>
//           <li>'.$studentList['studentName'][0].'</li>
//         </ul>';
//     }
//     echo '</div>';
// }
// echo '</div>';
?>

		</div><!-- #content -->
	</div><!-- #primary -->
  <?php // get_sidebar(); ?>

</div>
<?php get_footer(); ?>
