<?php
  include '../../../wp-load.php';

  if ( memb_getLoggedIn() && current_user_can('administrator') ) {
    $file = $_FILES['file'];
    $folder = "documents";
    $file_type = "file";
    $documentId = $_POST['documentId'];
    $s3Link = get_post_meta($documentId, 's3link', true);

    if($_FILES['file']['error'] == 0) {
        $document_file_link = es3_update_file( $file, $folder, $file_type, $s3Link );
    }

    $tagMemberLevels = [];
    $tagMemberLevels[] = $_POST['membershipLevel'];
    $tagMemberLevels[] = $_POST['infusionsoftTags'];

    $createPost = array (
        'ID' => $documentId,
        'post_title' => $_POST['name'],
        'post_content' => $_POST['description'],
        'post_type' => 'document',
        'post_status' => 'publish',
        'meta_input' => array (
            'userId' => $_POST['eventMentorByName'],
            'IStags' => $_POST['membershipLevel']
        )
    );

    $newDocumentId = wp_update_post( $createPost );

    wp_set_post_terms( $documentId, $_POST['category'], 'group' );

    if ( $newDocumentId ) {
      send_notification( array( 'content' => 'A document has been uploaded.', 'sender_id' => get_current_user_id(), 'user_id' => $_POST['eventMentorByName'], 'link' => get_permalink(499) ) );

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
