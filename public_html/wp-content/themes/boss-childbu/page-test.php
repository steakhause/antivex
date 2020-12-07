<?php
// get_header();

 // include '../../../wp-load.php';
 //
 // get_header();
//
// // TEST PURPOSES ONLY - KEEP, DO NOT COMMENT
// function get_meta( $id, $type = 'post' ){
//   if ( $type == 'post' ){
//     $meta = get_post_meta( $id );
//   }else if ( $type == 'user' ){
//     $meta = get_user_meta( $id );
//   }
// }

//   echo '<pre>';
//   print_r( $meta );
//   echo '</pre>';
// }

// get_meta( 17, 'user' );

// function update_event_to_user() {
//   $eventId = 131;
//   $userId = 17;
//
//   return update_user_meta($userId, 'registeredEvent', $eventId);
// }

// update_event_to_user();

// echo '<pre>'; print_r(get_user_meta(17, 'registeredEvent')); echo '</pre>';
// $eventId = 161;
// $tagIDs = array();
//
// $post1 = get_post_meta($eventId);
// echo '<pre>'; print_r($post1); echo '</pre>';

// $mentorsAndStudents = unserialize(get_post_meta($eventId, '_eventMentorsByName', true));
// echo '<pre>'; print_r($mentorsAndStudents); echo '</pre>';
// $count = 0;
// foreach ($mentorsAndStudents as $key => $tagIds) {
//   // $tagIDs[] = $key;
//   echo '<pre>'; print_r($tagIds); echo '</pre>';
//   for ($i=0; $i < sizeof($tagIds['studentList']['studentId']); $i++) {
//     $allStudents[] = $tagIds['studentList']['studentId'][$i];
//     $userId = $tagIds['studentList']['studentId'][$i];
//     update_user_meta($userId, 'registeredEvent', $eventId);
//   }
// }
//
//
// echo '<pre>'; print_r($allStudents); echo '</pre>';

// $eventId = 162;
// $userId = 24;
// // update_event_to_user($eventId, $userId);
// update_event_to_user_reset($eventId, $userId);
//
// echo '<pre>'; print_r(get_user_meta($userId, 'registeredEvent')); echo '</pre>';
//
// function update_event_to_user( $eventId, $userId ) {
// 	$registeredEvents = get_user_meta( $userId, 'registeredEvent' );
//
// 	if ( $registeredEvents ) {
//     if ( !in_array( $eventId, $registeredEvents ) ) {
//       add_user_meta( $userId, 'registeredEvent', $eventId );
//     } else {
//       return;
//     }
//   } else {
//     update_user_meta( $userId, 'registeredEvent', $eventId );
//   }
// }
//
// function update_event_to_user_reset($eventId, $userId) {
  // delete_user_meta( $userId, 'registeredEvent', $eventId );
// }

// $args = array (
//   'post_type' => 'document',
//   'posts_per_page' => -1,
//   'order' => 'ASC',
// );
//
// $the_query = new WP_Query( $args );
//
// if ( $the_query->have_posts() ) {
//   while ( $the_query->have_posts() ) {
//     $the_query->the_post();
//     the_title();
//   }
// }

// echo $_GET['search'].'<br />';
// echo $_GET['post_type'].'<br />';
// $search = $_GET['search'].'<br />';
// $post_type = $_GET['post_type'].'<br />';
//
// echo $_POST['search'].'<br />';
// echo $_POST['post_type'].'<br />';
// $search = $_POST['search'].'<br />';
// $post_type = $_POST['post_type'].'<br />';
//
// $the_query = new WP_Query( array(
//     'post_type' => 'document',
//     'name' => $search,
//     'tax_query' => array(
//         array (
//             'taxonomy' => 'group',
//             'field' => 'post_tag',
//             'terms' => 'Custom Group A',
//         )
//     ),
// ) );
//
// echo '<pre>'; print_r($the_query); echo '</pre>';
//
// echo 'here';

// if ( $the_query->have_posts() ) :
// while ( $the_query->have_posts() ) :
//     $the_query->the_post();
//     the_title();
// endwhile;
//
// wp_reset_postdata();
//
// endif;

// $search = 'test';
//
// global $wpdb;
//
// $dbquery = $wpdb->prepare( " SELECT post.post_title, post.post_content, term.name
// FROM `wp_gah12_posts` AS post, `wp_gah12_term_taxonomy` AS taxonomy, `wp_gah12_term_relationships` AS relation, `wp_gah12_terms` AS term
// WHERE post.ID = relation.object_id
// AND relation.term_taxonomy_id = taxonomy.term_id
// AND taxonomy.term_id = term.term_id
// AND post.post_type = 'document'
// AND taxonomy.taxonomy = 'group'
// AND post.post_title LIKE '%pneumonoultramicroscopicsilicovolcanoconiosis%'
// OR post.post_content LIKE '%pneumonoultramicroscopicsilicovolcanoconiosis%'
// OR term.name LIKE '%pneumonoultramicroscopicsilicovolcanoconiosis%' " );
//
// echo $wpdb->get_results($dbquery);
//
// echo '<pre>'; print_r($wpquery); echo '</pre>';

// global $wpdb;
// $audio_call_id = 4;
//
// $query_string = "SELECT link FROM ci_audio_calls WHERE id = %d";
// $query = $wpdb->prepare( $query_string, $audio_call_id );
// $s3_link = $wpdb->get_var( $query );
//
// $s3_link = explode( '/', $s3_link );
// echo '<pre>';
// print_r( $s3_link );
// echo '</pre>';
//
// $s3_key = $s3_link[(count($s3_link)-2)].'/'.$s3_link[(count($s3_link)-1)];
// echo $s3_key;
//
// // $s3_key = $s3_link;
//
// $s3Client = es3_connect();
//
// // Download the contents of the object.
// $result = $s3Client->deleteObject(
//   array(
//     'Bucket' => 'clever-investor-website',
//     'Key'    => $s3_key
//   )
// );
//
// echo '<h3>'.$result.'</h3>';





// echo Fep_Form::init()->form_field_output( 'newmessage' );

global $wp_filter;
echo '<pre>';
print_r( $wp_filter );
echo '</pre>'; 



// get_footer();
?>
