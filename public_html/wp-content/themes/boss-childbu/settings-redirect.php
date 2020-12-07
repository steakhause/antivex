<?php
  include '../../../wp-load.php';

  if( get_current_user_id() == $_POST['userId'] ) {
    if( isset( $_POST['emailPreference'] ) && $_POST['emailPreference'] == 'Yes' ) {
      update_user_meta( get_current_user_id(), 'email_notificaiton_preference', 'Yes' );

      $status_msg = "You have successfully edited your settings.";
      $status_type = "success";
    } else {
      update_user_meta(get_current_user_id(), 'email_notificaiton_preference', 'No');

      $status_msg = "You have successfully edited your settings.";
      $status_type = "success";
    }
  } else {
    $status_msg = "There was a problem with editing your settings.";
    $status_type = "error";
  }

  $redirect_link = add_query_arg( array(
    'status_msg' => $status_msg,
    'type' => $status_type
  ), wp_get_referer() );

  header( 'Location: '.$redirect_link );
?>
