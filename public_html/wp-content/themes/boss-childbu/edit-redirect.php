<?php
include '../../../wp-load.php';

if (memb_getLoggedIn() && ( current_user_can("fulfillment_role") || current_user_can('administrator') ) ) {
    if ($_POST['postId']) {
        $postId = $_POST['postId'];

        if ($_POST['eventTitle']) {
            update_post_meta($postId, 'eventTitle', sanitize_text_field($_POST['eventTitle']));
        }
        if ($_POST['eventDate']) {
            update_post_meta($postId, 'eventDate', $_POST['eventDate']);
        }
        if ($_POST['eventHour']) {
            update_post_meta($postId, 'eventHour', sanitize_text_field($_POST['eventHour']));
        }
        if ($_POST['eventMinute']) {
            update_post_meta($postId, 'eventMinute', sanitize_text_field($_POST['eventMinute']));
        }
        if ($_POST['eventAmPm']) {
            update_post_meta($postId, 'eventAmPm', sanitize_text_field($_POST['eventAmPm']));
        }
        if ($_POST['eventTimeZone']) {
            update_post_meta($postId, 'eventTimeZone', sanitize_text_field($_POST['eventTimeZone']));
        }
        if ($_POST['eventDescription']) {
            wp_update_post(array( 'ID'=>$postId, 'post_title'=>sanitize_text_field($_POST['eventTitle']), 'post_content'=>$_POST['eventDescription'] ));
        }
        if ($_POST['eventLocation']) {
            update_post_meta($postId, 'eventLocation', $_POST['eventLocation']);
        }
        if ($_POST['eventLocationLink']){
          update_post_meta($postId, 'eventLocationLink', $_POST['eventLocationLink']);
        }
        if ($_POST['eventMentorByTag']) {
            update_post_meta($postId, '_eventMentorByTag', $_POST['eventMentorByTag']);
        }

    if (array_key_exists('eventMentorByTag', $_POST)) {
        //Loop through WP userIds
        $wpUsers = get_users(['role__in' => ['mentoring_student_role', 'mentor_role']]);
        $tagList = $_POST['eventMentorByTag'];
        $userList = [];
        foreach ($wpUsers as $user) {
            $contactId = get_user_meta($user->ID, 'infusionsoft_user_id', true);

            if (memb_hasAnyTags($tagList, $contactId)) {
                $userList[] = $user->ID;
            }
        }

        if($userList) {
            send_notification(array( 'content' => 'An event has been updated.', 'sender_id' => get_current_user_id(), 'user_id' => $userList, 'link' => get_permalink($postId) ));
        }
    }
        if ($_POST['eventMentorByName']) {
            update_post_meta($postId, '_eventMentorByName', $_POST['eventMentorByName']);

            foreach ($_POST['eventMentorByName'] as $key => $user) {
                update_event_to_user($postId, $key);
            }
        }
    } else {
        $createPost = array(
            'post_title' => $_POST['eventTitle'],
            'post_content' => $_POST['eventDescription'],
            'post_type' => 'event',
            'post_status' => 'publish',
            'meta_input' => array(
            'eventTitle' => $_POST['eventTitle'],
            'eventDate' => $_POST['eventDate'],
            'eventHour' => $_POST['eventHour'],
            'eventMinute' => $_POST['eventMinute'],
            'eventAmPm' => $_POST['eventAmPm'],
            'eventTimeZone' => $_POST['eventTimeZone'],
            'eventLocation' => $_POST['eventLocation'],
            'eventLocationLink' => $_POST['eventLocationLink'],
            '_eventMentorByTag' => $_POST['eventMentorByTag'],
            '_eventMentorByName' => $_POST['eventMentorByName'],
            )
        );

        $newEventId = wp_insert_post($createPost);

        if ($newEventId) {
            if (array_key_exists('eventMentorByTag', $_POST)) {
                //Loop through WP userIds
                $wpUsers = get_users(['role__in' => ['mentoring_student_role', 'mentor_role']]);
                $tagList = $_POST['eventMentorByTag'];
                $userList = [];
                foreach ($wpUsers as $user) {
                    $contactId = get_user_meta($user->ID, 'infusionsoft_user_id', true);

                    if (memb_hasAnyTags($tagList, $contactId)) {
                        $userList[] = $user->ID;
                    }
                }

                if($userList) {
                    send_notification(array( 'content' => 'An event has been updated.', 'sender_id' => get_current_user_id(), 'user_id' => $userList, 'link' => get_permalink($newEventId) ));
                }
            }
            if ($_POST['eventMentorByName']) {
                foreach ($_POST['eventMentorByName'] as $key => $user) {
                    update_event_to_user($newEventId, $user);

                    send_notification(array( 'content' => 'An event has been updated.', 'sender_id' => get_current_user_id(), 'user_id' => $user, 'link' => get_permalink($newEventId) ));
                }
            }

            $status_msg = "You have successfully created a new event.";
            $status_type = "success";
        }else{
          $status_msg = "There was a problem creating a new event.";
          $status_type = "error";
        }
    }
}else{
  $status_msg = "There was a problem creating a new event.";
  $status_type = "error";
}

if ( $_POST['clone'] ){
  $referer = add_query_arg( array(
    'eventId' => $newEventId,
  ), get_permalink(65)
  );
}else{
  $referer = wp_get_referer();
}

$redirect_link = add_query_arg( array(
  'status_msg' => $status_msg,
  'type' => $status_type
), $referer );

header( 'Location: '.$redirect_link );
