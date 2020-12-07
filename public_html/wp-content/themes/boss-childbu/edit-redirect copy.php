<?php
  include '../../../wp-load.php';

  if ( memb_getLoggedIn() && ( get_user_role()=='fulfillment_role' ) ) {
    if($_POST['postId']) {
      $postId = $_POST['postId'];

      if($_POST['eventTitle']) { update_post_meta( $postId, 'eventTitle', $_POST['eventTitle'] ); }
      if($_POST['eventDate']) { update_post_meta( $postId, 'eventDate', $_POST['eventDate'] ); }
      if($_POST['eventHour']) { update_post_meta( $postId, 'eventHour', $_POST['eventHour'] ); }
      if($_POST['eventMinute']) { update_post_meta( $postId, 'eventMinute', $_POST['eventMinute'] ); }
      if($_POST['eventAmPm']) { update_post_meta( $postId, 'eventAmPm', $_POST['eventAmPm'] ); }
      if($_POST['eventTimeZone']) { update_post_meta( $postId, 'eventTimeZone', $_POST['eventTimeZone'] ); }
      if($_POST['eventDescription']) { wp_update_post(array( 'ID'=>$postId, 'post_title'=>$_POST['eventTitle'], 'post_content'=>$_POST['eventDescription'] )); }
      if($_POST['eventLocation']) { update_post_meta( $postId, 'eventLocation', $_POST['eventLocation'] ); }
      if($_POST['eventMentorByTag']) {
    	  update_post_meta( $postId, '_eventMentorByTag', serialize($_POST['eventMentorByTag']) );echo 'this didnt break 2.5';
    	  foreach($_POST['eventMentorByTag'] as $keys => $users) {
    	    foreach($users as $key => $user) { echo 'here'; echo '<pre>'; print_r($user); echo '</pre>';
      		  foreach($user as $keyValues => $students) {
      		    if ($keyValues=="studentId") {
        			  foreach($students as $keyValue => $student)
                { update_event_to_user( $postId, $student ); }
      		    }
              send_notification( array( 'content' => 'An event has been updated.', 'sender_id' => get_current_user_id(), 'user_id' => $students ) );
      		  }
    	    }
    	  }
    	}
      if($_POST['eventMentorByName']) {
    	  update_post_meta( $postId, '_eventMentorByName', serialize($_POST['eventMentorByName']) );
    	  foreach($_POST['eventMentorByName'] as $key => $user){
          update_event_to_user( $postId, $key );
        }
    	}
    } else {
      $createPost = array (
        'post_title' => $_POST['eventTitle'],
        'post_content' => $_POST['eventDescription'],
        'post_type' => 'event',
        'post_status' => 'publish',
        'meta_input' => array (
          'eventTitle' => $_POST['eventTitle'],
          'eventDate' => $_POST['eventDate'],
          'eventHour' => $_POST['eventHour'],
          'eventMinute' => $_POST['eventMinute'],
          'eventAmPm' => $_POST['eventAmPm'],
          'eventTimeZone' => $_POST['eventTimeZone'],
          'eventLocation' => $_POST['eventLocation'],
          '_eventMentorByTag' => serialize($_POST['eventMentorByTag']),
          '_eventMentorByName' => serialize($_POST['eventMentorByName']),
        )
      );

      $newEventId = wp_insert_post( $createPost );

      if ( $newEventId ) {
        $eventAttendees = explode(",", $_POST['eventAttendees']);
        foreach ( $eventAttendees as $eventAttendee ) {
          update_event_to_user( $newEventId, $eventAttendee );
        }
        send_notification( array( 'content' => 'An event has been created.', 'sender_id' => get_current_user_id(), 'user_id' => $eventAttendees ) );
      }
    }
  }

?>
