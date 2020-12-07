<?php
  include '../../../wp-load.php';

  if ( memb_getLoggedIn() && ( current_user_can("fulfillment_role") || current_user_can("mentor_role") || current_user_can('administrator') ) ) {
    $file = $_FILES['file'];
    $folder = "documents";
    $file_type = "file";
    $fileName = $_POST['name'];

    $document_file_link = es3_upload_without_storing( $file, $folder, $file_type );

    $tagMemberLevels = [];
    $tagMemberLevels[] = $_POST['membershipLevel'];
    $tagMemberLevels[] = $_POST['infusionsoftTags'];

    $createPost = array (
      'post_title' => $_POST['name'],
      'post_content' => $_POST['description'],
      'post_type' => 'document',
      'post_status' => 'publish',
      'meta_input' => array (
        's3link' => $document_file_link,
        'userId' => $_POST['notification'],
        'IStags' => $_POST['membershipLevel'] . ',' . $_POST['infusionsoftTags']
      )
    );

    $newDocumentId = wp_insert_post( $createPost );

    wp_set_post_terms( $newDocumentId, $_POST['category'], 'group' );

    if ( $newDocumentId ) {
      send_notification( array( 'content' => 'A document has been uploaded.', 'sender_id' => get_current_user_id(), 'user_id' => $_POST['notification'], 'link' => get_permalink(499) ) );

      $status_msg = "You have successfully uploaded a document.";
      $status_type = "success";
    }else{
      $status_msg = "There was a problem uploading the document.";
      $status_type = "error";
    }
  }else{
    $status_msg = "There was a problem uploading the document.";
    $status_type = "error";
  }

  $redirect_link = add_query_arg( array(
    'status_msg' => $status_msg,
    'type' => $status_type,
  ), wp_get_referer() );

  header( 'Location: '.$redirect_link );

?>
