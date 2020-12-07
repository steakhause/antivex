<?php
/**
 * Template Name: Audio Calls Page Template
 *
 * Description: Use this page template for audio calls page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

if ( $_GET['student'] ){
	$viewing_user = sanitize_text_field( $_GET['student'] );
	$only_edit_viewing = true;
}else{
	$viewing_user = get_current_user_id();
	$only_edit_viewing = false;
}

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

?>

<div class="audio">

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
							exit_with_footer("You do not have permission to view this user's audio calls.");
						}

						if ( current_user_can("mentor_role") && !is_assigned_to( $viewing_user )  && get_current_user_id() != $viewing_user ){
							exit_with_footer("You do not have permission to view this user's audio calls.");
						}
						?>

						<?php if ( !current_user_can("mentoring_student_role") && current_user_can("mentor_role") ) { ?>
            <form method="post" action="<?php echo site_url().'/index.php?audio_calls=1'; ?>"  enctype="multipart/form-data">
              <div class="form-row">
                <label for="file_name">File Name</label>
                <input type="text" name="file_name">
              </div>
              <div class="form-row">
                <input class="upload_this" type="file" name="upload_this" size="40">
              </div>

							<?php if ( !$only_edit_viewing ){ ?>

							<ul class="editWindow">
								<li class="form-row">
									<label for="form-checkbox">Select Recipients</label><br />
									<?php
										$mentorList = get_users(array('role'=>'mentor_role'));
										$studentList = get_users(array('role'=>'mentoring_student_role'));

										if ( $mentorList ) {
											foreach ( $mentorList as $mentorKeys => $mentors ) {
												if ( current_user_can("fulfillment_role") || $mentors->data->ID == get_current_user_id() ) {
													echo '<div class="displayAllMentorsAndStudents">';
														if ( current_user_can("fulfillment_role") ) {
															echo '<div class="allCheckContainer"><input type="radio" class="mentorCheck eachCheck" name="user_id" value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</div>';
														}
														if ( $studentList ) {
															echo '<ul class="displayAllMentoringStudents">';
															foreach ( $studentList as $studentKeys => $students ) {
																if (get_user_meta($students->data->ID)['_assignedMentor'][0] == $mentors->data->ID) {
																	echo '<li><input type="radio" class="studentCheck eachCheck" name="user_id" value="'.$students->data->ID.'">'.$students->data->display_name.'</li>';
																}
															}
															echo '</ul>';
														}
													echo '</div>';
												}
											}
										}
									?>
								</li>
							</ul>

							<?php
							}else{
								echo '<input type="hidden" name="user_id" value="'.$viewing_user.'">';
							} ?>

              <input type="submit" name="submit" value="Upload Media">
            </form>
						<?php } ?>

            <div class="audio-calls">			

              <!--<div class="documentDownload"><a href="'.$audio_call['link'].'" target="_blank"><span class="icon-download2"></span></a></div>-->
              <?php
              $audio_calls = get_audio_calls( $viewing_user );

              if ( $audio_calls ){
									echo '<ul class="documentList">';
		                echo '<li class="documentLi"><div class="documentName">File Name</div><div class="documentFrom">From</div><div class="documentDate">Date</div><div class="documentDownload">Player</div></li>';
										foreach ( $audio_calls as $audio_call ){
											$uploader = get_userdata( $audio_call['uploader_id'] );

											echo '<li class="documentLi">
												<div class="documentName"><span class="icon-file-text2"></span> '.$audio_call['name'].'</div>
		                    <div class="documentFrom">'.$uploader->data->display_name.'</div>
		                    <div class="documentDate">'.$audio_call['date'].'</div>
							<video class="documentDownload" controls controlsList="nodownload" >
				                Your browser does not support the <code>video</code> element.
				                <source src="'.$audio_call['link'].'"  type="video/mp4">
              				</video>
		                  </li>';
		                }
		              echo '</ul>';
              } else {
              		echo '<p>You currently do not have any media.</p>';
              }
              ?>
            </div>
          </div>

      </article>
    </div>
  </div>
</div>

<?php
get_footer();

 ?>
