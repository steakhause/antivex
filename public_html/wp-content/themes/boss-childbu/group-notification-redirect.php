<?php
  include '../../../wp-load.php';

  // check to see if the request is from a logged in user, and its either fulfullment or mentor
  if ( memb_getLoggedIn() && ( current_user_can("fulfillment_role") || current_user_can("administrator") || current_user_can("mentor_role") ) ) {

    global $wpdb;

    // check to see if post is not empty
    if(!empty($_POST['notification'])) {

      // store post data
      $userIds = filter_var_array($_POST['notification'], FILTER_SANITIZE_NUMBER_INT);
      $notification = filter_var($_POST['notificationContent'], FILTER_SANITIZE_STRING);
      $senderId = filter_var( (int) $_POST['senderId'], FILTER_SANITIZE_NUMBER_INT);

      $notificationData = [
          'content' => $notification,
          'sender_id' => $senderId,
          'user_id' => $userIds
      ];

      send_notification( $notificationData );

      $status_msg = "You have successfully sent out a notification.";
      $status_type = "success";
    }else{
      $status_msg = "There was a problem creating a new notification.";
      $status_type = "error";
    }
  }else{
    $status_msg = "There was a problem creating a new notification.";
    $status_type = "error";
  }

  $redirect_link = add_query_arg( array(
    'status_msg' => $status_msg,
    'type' => $status_type
  ), wp_get_referer() );

  header( 'Location: '.$redirect_link );
?>
