<?php
  include '../../../wp-load.php';

  if(current_user_can("fulfillment_role")) {
    if($_GET['postId']) {
      $postId = $_GET['postId'];

      // delete the event from user meta based on tag
      $listByTag = unserialize(get_post_meta($postId, '_eventMentorByTag')[0]);
      foreach ( $listByTag as $values ) {
        foreach ( $values as $value ) {
          foreach ( $value['studentId'] as $user ){
            $success = delete_user_meta( $user, 'registeredEvent', $postId );
          }
        }
      }

      // delete the evnt from user meta based on mentor
      $listByMentor = unserialize(get_post_meta($postId, '_eventMentorByName')[0]);
      foreach ( $listByMentor as $values ) {
        foreach ( $values as $value ) {
          foreach ( $value['studentId'] as $user ){
            $success = delete_user_meta( $user, 'registeredEvent', $postId );
          }
        }
      }

      // delete the post
      $success = wp_delete_post($postId);

      if ( $success ){
        $status_msg = "You have successfully deleted that event";
        $status_type = "success";
      }else{
        $status_msg = "There was a problem deleting that event.";
        $status_type = "error";
      }
   }else{
     $status_msg = "There was a problem deleting that event.";
     $status_type = "error";
   }
 }else{
   $status_msg = "You do not have permission to delete that event.";
   $status_type = "error";
 }

 $redirect_link = add_query_arg( array(
   'status_msg' => $status_msg,
   'type' => $status_type
 ), wp_get_referer() );

 header( 'Location: '.$redirect_link );
?>
