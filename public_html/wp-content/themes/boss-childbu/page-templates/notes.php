<?php
/**
 * Template Name: Notes Page Template
 *
 * Description: Use this page template for audio calls page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

/* Banner images code Begin */
$userId = get_current_user_id();

 if ( $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }


 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

	$banner_image = get_user_meta( $user_id, 'banner_image', true );
/* Banner images code end */

 date_default_timezone_set("America/New_York");

// if ( $_GET['student'] ){
// 	$viewing_user = sanitize_text_field( $_GET['student'] );
// 	$only_edit_viewing = true;
// }else{
// 	$viewing_user = get_current_user_id();
// 	$only_edit_viewing = false;
// }

$viewing_user = get_current_user_id();

?>

<div class="notes">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article <?php post_class(); ?>>
					<header class="entry-header" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
							<div class="editEvent"></div>
							<h1 class="entry-title"><?php the_title(); ?></h1>
							<div class="editEvent"></div>
					</header>

          <div class="entry-content">
						<?php
						if ( current_user_can("mentoring_student_role") && get_current_user_id() != $viewing_user ){
							exit_with_footer("You do not have permission to view this user's notes.");
						}

						if ( current_user_can("mentor_role") && !is_assigned_to( $viewing_user )  && get_current_user_id() != $viewing_user ){
							exit_with_footer("You do not have permission to view this user's notes.");
						}
						 ?>
						<!-- <button class="addNoteBtn">Add Note</button> -->

						<?php
						$page = ( get_query_var('page') ? get_query_var('page') - 1 : 0 );
						$limit = 5;
						$notes = get_notes_by_user( $viewing_user, $limit, $page );
						$note_count = get_note_number_by_user( $viewing_user );
						$pages = round( $note_count / $limit );
						?>

						<div class="notes-list">
							<?php

								if ( $notes ){
									foreach ( $notes as $note ){
										if ( $note['sender_id'] > 0 ){
											$sender = get_userdata( $note['sender_id'] );
											$sender = $sender->display_name;
										}else{
											$sender = "Admin";
										}

										echo '<div class="note">
											<h3><span class="icon-file-text2"></span> '.stripslashes($note['title']).'</h3>
											<h3 class="subtext">Posted by '.$sender.' on '.date( 'F j, Y g:i a T', strtotime( $note['date_posted'] ) ).'</h3>
											<p>'.stripslashes($note['content']).'</p>
										</div>';
									}
								} else {
                                    echo "<p>You currently don't have any notes</p>";
                                }

echo '</div>';

								for( $i = 1; $i <= $pages; $i++ ){
									$link = add_query_arg( array(
									    'page' => $i
									), get_permalink() );
									echo '<span class="nav-page'.($i == ($page+1) ? ' current' : '').'"><a href="'.$link.'">'.$i.'</a></span>';
								}
							 ?>


          </div>

      </article>
    </div>
  </div>
</div>

<?php
get_footer();

 ?>
