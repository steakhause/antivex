<?php
  include '../../../wp-load.php';

  if ( memb_getLoggedIn() ) {
    global $wpdb;

    if(!empty($_POST['notification'])) {
      $userId = $_POST['userId'];
      $notifications = $_POST['notification'];
      $notificationPlaceholder = implode(',', array_fill(0, count($notifications), '%d' ));

      // prepare the Assigned Notifications table and delete the passed in notification
      $assignedSql = "DELETE FROM ci_assigned_notifications WHERE user_id = $userId AND notification_id IN ($notificationPlaceholder)";
      $assignedQuery = $wpdb->query($wpdb->prepare( $assignedSql, $notifications ) );

      if ( $assignedQuery ){
        $status_msg = "Your selected notifications have been deleted.";
        $status_type = "success";
      }else{
        $status_msg = "There was a problem deleting your notifications.";
        $status_type = "error";
      }
    }
    $redirect_link = add_query_arg( array(
      'status_msg' => $status_msg,
      'type' => $status_type
    ), wp_get_referer() );

    header( 'Location: '.$redirect_link );
  }
?>
