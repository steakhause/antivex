<?php
/**
 * Template Name: Profile Page Template 2
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 get_header();

 date_default_timezone_set("America/New_York");

 if ( $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }
global $wpdb;
 $_SESSION['$user_id'] = $user_id;

 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

 if( $user_id == get_current_user_id() ) {
     $userInfo = $_SESSION['memb_user'];
     $userExtendedInfo = $_SESSION['memb_db_fields'];
 } else {
     $getContactId = memb_getContactIdByUserId( $user_id );
     $getContact = memb_loadContactById($getContactId);
     $conFirstName = $getContact['FirstName'];
     $conLastName = $getContact['LastName'];
     $conEmail = $getContact['Email'];
     $conPhone = $getContact['Phone1'];
     $conStreetAddress = $getContact['StreetAddress1'];
     $conCity = $getContact['City'];
     $conState = $getContact['State'];
     $conPostalCode = $getContact['PostalCode'];

     $conDisplayName = $conFirstName . ' ' . $conLastName;
 }

 $mentorForm = get_post_meta(get_the_ID(), 'mentor_tools_form', true);
 $vipForm = get_post_meta(get_the_ID(), 'vip_form', true);

 // echo $user_id.'<br>';
 // echo $getContactId.'<br>';
 // echo $user_role;
 // echo ( current_user_can("fulfillment_role") ? 'fulfillment' : '' );

$mentor_id = (int)get_user_meta( $user_id, '_assignedMentor', true );

if(current_user_can('administrator') || current_user_can( 'fulfillment_role') || current_user_can( 'mentor_role') ) {
?>

<style type="text/css">
.editNoteOL {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 99;
}
.editNoteModal {
    position: fixed;
    border-radius: 4px;
    top: 10vh;
    left: 0;
    right: 0;
    margin: 0 auto;
    background-color: #FFF;
    z-index: 100;
    max-width: 580px;
    text-align: center;
}
.editNoteModal .exitNoteEditModal {
    position: absolute;
    cursor: pointer;
    top: -28px;
    right: -28px;
    border: 2px solid #FFF;
    border-radius: 50%;
    height: 24px;
    width: 24px;
    text-align: center;
    font-size: 16px;
    line-height: 18px;
    color: #FFF;
}
.editNoteModal input, .editNoteModal textarea, .editNoteModal select {
    width: 90%;
    padding: 14px;
    margin: 0 auto 16px;
    position: relative;
}
.editNoteModal .editNoteForm h3 {text-align: left;padding: 10px 5%;}
.editNoteModal .editNoteForm span.editNoteSubmit, .editNoteModal .editNoteForm span.editNoteCancel {
    padding: 8px 14px;
    margin: 0 0 16px 5%;
    display: block;
    float: left;
    border: 2px solid;
    border-radius: 4px;
    cursor: pointer;
}
input[type="text"]:not([name="s"]):focus, input[type="email"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, textarea:focus {
  color:#222222 !important;
}
</style>

    <script>
        jQuery(function() {
            function guwGetUrlParameter(name) {
                name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
                var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
                var results = regex.exec(location.search);
                return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
            };

            if(guwGetUrlParameter('pagenum').length > 0) {
                jQuery('html, body').delay(1000).animate({
                    scrollTop: jQuery("#notesWrap").offset().top
                }, 2000);
            }

            jQuery('input[name="inf_field_FirstName"]').each(function() {
                jQuery(this).val('<?php echo addslashes($conFirstName); ?>');
            });

            jQuery('input[name="inf_field_LastName"]').each(function() {
                jQuery(this).val('<?php echo addslashes($conLastName); ?>');
            });

            jQuery('input[name="inf_field_Email"]').each(function() {
                jQuery(this).val('<?php echo $conEmail; ?>');
            });
            
            jQuery('input[name="inf_custom_LoggedinUserName"').each(function(){
                jQuery(this).val(jQuery('.display-name').html());
            });
        });

        jQuery(document).ready(function(){
          jQuery('.tabs li').on('click', function(){
            var tab = jQuery(this).attr('data-type');

            jQuery('.tab-content.active').hide().removeClass('active');
            jQuery('.tab-content.'+tab).fadeIn(500).addClass('active');
            jQuery('.tab.active').removeClass('active');
            jQuery('.tab[data-type='+tab+']').addClass('active'); <?php /* E.N.: attribute selector is more specific/accurate */ ?>
          });

          jQuery('body').on("click", '.edit-note', function(){
            var editNoteParent = jQuery(this).parent().parent();
            var editNoteID = jQuery(this).data("id");
            var editNoteTitle = jQuery(editNoteParent).find('.noteTitle').find('.noteTitleContent').text();
            var editNoteContent = jQuery(editNoteParent).find('.noteContent').text();
            console.log(editNoteContent);
            var editNotePrivacy = jQuery(this).closest('.note').data("note-privacy");
            var modalMrkp = '<div class="editNoteOL"></div><div class="editNoteModal"><div class="exitNoteEditModal">X</div><form class="editNoteForm"><h3>Edit Note</h3><input type="hidden" name="action" value="updateUserNote"/><input type="hidden" name="noteID" value="'+editNoteID+'"/><input type="text" name="editNoteTitle" value="'+editNoteTitle+'"/><textarea name="editNoteContent" value="'+editNoteContent+'">'+editNoteContent+'</textarea><select name="editNotePrivacy">';
            if(editNotePrivacy !== "1"){
              modalMrkp = modalMrkp + '<option value="1" selected>Private</option><option value="0">Public</option>';
            } else {
              modalMrkp = modalMrkp + '<option value="1">Private</option><option value="0" selected>Public</option>';
            }
            var modalMrkp = modalMrkp + '</select><span class="editNoteSubmit">Update</span><span class="editNoteCancel">Cancel</span></form></div>';
            jQuery('body').prepend(modalMrkp);

            jQuery('body').on("click", ".exitNoteEditModal, .editNoteCancel", function(){
              jQuery('.editNoteModal').empty();
              jQuery('.editNoteModal').remove();
              jQuery('.editNoteOL').remove();
            });

            jQuery('body').on("click", ".editNoteSubmit", function(){
              var formAction = jQuery('input[name="action"]').val();
              var serArr = jQuery('.editNoteForm').serializeArray();
              jQuery.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                data: serArr,
                dataType: "json",
                success: function(data){
                  if(data['data'] === 1){
                    jQuery(editNoteParent).find('.noteTitle').html('<span class="icon-file-text2"></span> <span class="noteTitleContent">'+serArr[2].value+'</span> - <span class="edit-note" data-id="'+editNoteID+'">Edit</span>');
                    jQuery(editNoteParent).find('.noteContent').replaceWith('<p class="noteContent">'+serArr[3].value+'</p>');
                    if(serArr[4].value == "1"){
                      if(jQuery(editNoteParent).find('.note-private').length < 1){
                          jQuery(editNoteParent).find('.noteContent').before('<p class="note-private" style="color: #759430 !important;">Private Note</p>');
                      }
                    } else {
                      jQuery(editNoteParent).find('.note-private').remove();
                    }
                    jQuery('.editNoteModal').empty();
                    jQuery('.editNoteModal').remove();
                    jQuery('.editNoteOL').remove();
                  }
                },
                error: function(data){
                  console.log(data['data']);
                }
              });
            });
          });
          jQuery('.av_call_modal_btn').on('click',function(){
            var modalId = jQuery(this).data('av_call');
            jQuery('div#'+modalId).css("display", "block");
          });
          jQuery('.av_call_modal_close').on('click',function(){
            jQuery(this).parent().find('video').get(0).pause();
            jQuery(this).parent().css("display", "none");
          });

          <?php if(!current_user_can('fulfillment_role')){echo 'jQuery("#inf_custom_AdviserNotes").hide().val(""); jQuery("label[for=\'inf_custom_AdviserNotes\']").hide();';} ?>


          jQuery('#inf_option_AlertFulfillment').change(function(){
            if(jQuery('#inf_option_AlertFulfillment').attr('checked') == 'checked'){
              jQuery('#inf_custom_AdviserNotes').show();
              $('label[for="inf_custom_AdviserNotes"]').show();
            }else{
              jQuery('#inf_custom_AdviserNotes').hide().val("");
              jQuery('label[for="inf_custom_AdviserNotes"]').hide();
            }
          });
        });

    </script>

    <div id="user-settings" class="profile user-settings">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <?php
         /* $banner = get_user_meta( $user_id, '_profile_banner', true );

          if ( $banner ){
            $banner_image = $banner;
          }else{
            $banner_image = get_the_post_thumbnail_url( get_the_ID(), 'large' );
          }*/
$banner_image = get_user_meta($user_id, 'banner_image', true);
          ?>

          <article <?php post_class(); ?>>
              <header class="entry-header" <?php if($banner_image) { ?>style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover; padding: 30px !important;" <?php } ?>>

                  <!-- page title -->
                  <div class="profile-image"><?=get_avatar( $user_id, 96 ); ?></div>
                  <h1 class="entry-title"><?php echo get_user_meta( $user_id, 'first_name', true ).' '.get_user_meta( $user_id, 'last_name', true ); ?></h1>
                  <div class="profile-badges">
                    <?php
                      $badges = badgeos_get_user_achievements( array( 'user_id' => $user_id ) );

                      if ( $badges ){
                        foreach ( $badges as $badge ){
                          echo badgeos_get_achievement_post_thumbnail( $badge->ID );
                        }
                      }
                     ?>
                  </div>

                  <div class="message_btn" style="margin-top: 20px;">
                    <?php if ( get_current_user_id() != $user_id ){
                      echo '<a href="'.site_url().'/messages/?fepaction=newmessage&to='.$user_id.'"><button>Message</button></a>';
                    } ?>
                  </div>
              </header>

              <div class="entry-content">

                <?php if ( $_GET['success'] ){
                  echo '<div class="success"><p>'.$_GET['success'].'</p></div>';
                }

                  // Get page content if there is any to allow for an editable description.
          				if ( have_posts() ) {
          					while ( have_posts() ) {
          						the_post();
          						the_content();
          					}
          				}
                ?>

                <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role') ) :  ?>
                  <ul class="tabs menu_profile">
                    <li data-type="information" class="tab information<?php echo ( $_GET['current'] ? '' : ' active' ); ?>">Information</li>
                    <li data-type="courses" class="tab courses<?php echo ( $_GET['current'] == 'courses' ? ' active' : '' ); ?>">Courses</li>
                    <li data-type="deal-review" class="tab deal-review<?php echo ( $_GET['current'] == 'dealreview' ? ' active' : '' ); ?>">Deal Reviews</li>
                    <li data-type="assignments" class="tab assignments<?php echo ( $_GET['current'] == 'assignments' ? ' active' : '' ); ?>">Assignments</li>
                    <li data-type="audio" class="tab audio<?php echo ( $_GET['current'] == 'audio' ? ' active' : '' ); ?>">Mentoring Sessions</li>
                    <li data-type="notes" class="tab notes<?php echo ( $_GET['current'] == 'notes' ? ' active' : '' ); ?>">Notes</li>
                    <li data-type="events" class="tab events<?php echo ( $_GET['current'] == 'events' ? ' active' : '' ); ?>">Appts &amp; Events</li>
                    <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role')) : ?>
                      <li data-type="fulfillment-tools" class="tab fulfillment<?php echo ( $_GET['current'] == 'fulfillmenttools' ? ' active' : '' ); ?>">Rewards</li>
                    <?php endif; ?>
                    <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role')) { ?>
                        <li data-type="mentor-tools" class="tab fulfillment<?php echo ( $_GET['current'] == 'mentortools' ? ' active' : '' ); ?>">Mentor Tools</li>
                    <?php } ?>
                    <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') ) { ?>
                        <li data-type="vip-form" class="tab fulfillment<?php echo ( $_GET['current'] == 'vipform' ? ' active' : '' ); ?>">VIP Form</li>
                    <?php } ?>
                    <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') ) { ?>
                        <li data-type="badges" class="tab fulfillment<?php echo ( $_GET['current'] == 'badges' ? ' active' : '' ); ?>">Badges</li>
                    <?php } ?>

                    <?php /* if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role')) { ?>
                        <li data-type="my_team" class="tab fulfillment<?php echo ( $_GET['current'] == 'my_team' ? ' active' : '' ); ?>">My Team</li>
                    <?php } */ ?>

                  </ul>
                <?php endif; ?>

                <div class="tab-content information<?php echo ( $_GET['current'] ? '' : ' active' ); ?>">Information
                  <?php



                    if ( !current_user_can('mentoring_student_role') && !current_user_can('student_role') && $user_id == $_GET['user_id'] ) {

                        echo '<p class="profile-info"><strong>Name:</strong> '.$conDisplayName.'</p>';
                        echo '<p class="profile-info"><strong>Email:</strong> '.$conEmail.'</p>';
                        if(!empty($conStreetAddress)) {
                            echo '<p class="profile-info"><strong>Address:</strong> '.$conStreetAddress.'<br />' . $conCity . ', ' . $conState . ' ' . $conPostalCode . '</p>';
                        }
                        echo '<p class="profile-info"><strong>Phone:</strong> '.$conPhone.'</p>';
                        if ( $user_role == "mentoring_student_role" ){
                          echo '<p class="profile-info"><strong>Mentor:</strong> '.get_user_meta($user_meta['_assignedMentor'][0], 'nickname')[0].'</p>';
                        }
                        echo '<p class="profile-info"><strong>Program:</strong> '.$user_meta['level'][0].'</p>';
                        // echo $user_meta['wp_gah12_user_avatar'][0];

                        echo '<hr /><p class="profile-info"><strong>Their Why:</strong> '.$user_meta['theirWhy'][0].'</p>';
                        echo '<p class="profile-info"><strong>Goals:</strong> '.$user_meta['goals'][0].'</p>';

                        echo '<p class="profile-info"><strong>Experience Level:</strong> '.$user_meta['experienceLevel'][0].'</p>';
                        echo '<p class="profile-info"><strong>Main Focus:</strong> '.$user_meta['mainFocus'][0].'</p>';
                        echo '<p class="profile-info"><strong>Tech Level:</strong> '.$user_meta['techLevel'][0].'</p>';
                        echo '<p class="profile-info"><strong>Fears:</strong> '.$user_meta['fears'][0].'</p>';


                  } else if (!current_user_can('mentoring_student_role') && !current_user_can('student_role') && $user_id == get_current_user_id()) {
                      echo '<p class="profile-info"><strong>Name:</strong> '.$userInfo['DisplayName'].'</p>';
                      echo '<p class="profile-info"><strong>Email:</strong> '.$userInfo['Email'].'</p>';
                      if(!empty($userExtendedInfo['streetaddress1'])) {
                          echo '<p class="profile-info"><strong>Address:</strong> '.$userExtendedInfo['streetaddress1'].'<br />' . $userExtendedInfo['city'] . ', ' . $userExtendedInfo['state'] . ' ' . $userExtendedInfo['postalcode'] . '</p>';
                      }
                      echo '<p class="profile-info"><strong>Phone:</strong> '.$userExtendedInfo['phone1'].'</p>';
                      if ( $user_role == "mentoring_student_role" ){
                        echo '<p class="profile-info"><strong>Mentor:</strong> '.get_user_meta($user_meta['_assignedMentor'][0], 'nickname')[0].'</p>';
                      }
//                      echo $user_meta[$wpdb->base_prefix . 'user_avatar'][0];
                  }
                  ?>
                </div>

                <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role') ) : ?>

                  <div class="tab-content courses<?php echo ( $_GET['current'] == "courses" ? ' active' : '' ); ?>">
                    <h2>Course Progress</h2>
                    <?php do_shortcode('[guw_ld_profile_custom]'); ?>
                  </div>

                  <div class="tab-content deal-review<?php echo ( $_GET['current'] == "dealreview" ? ' active' : '' ); ?>">
                    <h3>Single Family</h3>
                    <?php echo do_shortcode('[guw_get_deal_reviews]'); ?>
                    <h3>Income Producing / Rentals</h3>
                    <?php echo do_shortcode('[guw_get_mf_deal_reviews]'); ?>
                  </div>

                  <div class="tab-content assignments<?php echo ( $_GET['current'] == "assignments" ? ' active' : '' ); ?>">
                    <?php echo do_shortcode('[guw_get_assignments_for_user]'); ?>
                  </div>

                  <div class="tab-content audio<?php echo ( $_GET['current'] == "audio" ? ' active' : '' ); ?>">
                    <?php if ( current_user_can("fulfillment_role") || current_user_can("administrator") ){ ?>
                    <form method="post" action="<?php echo site_url().'/index.php?audio_calls=1'; ?>"  enctype="multipart/form-data">
                      <div class="form-row">
                        <label for="file_name">File Name</label>
                        <input type="text" name="file_name">
                      </div>
                      <div class="form-row">
                        <input class="upload_this" type="file" name="upload_this" size="40">
                      </div>

        							<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">

                      <input type="submit" name="submit" value="Upload Mentoring Session">

                    </form>
        						<?php } ?>

                    <?php echo do_shortcode('[guw_get_user_audio_calls]'); ?>
                  </div>

                  <div class="tab-content notes<?php echo ( $_GET['current'] == "notes" ? ' active' : '' ); ?>">
                      <!-- <div class="addNoteOverlay"> -->
                        <div class="addNoteBox">
                      		<div class="noteHeader">Add Note</div>
                      		<div class="noteContainer">
                      			<form method="post" action="<?php echo site_url().'/index.php?notes=1'; ?>">
                      				<div class="form-row">
                      					<label for="title">Title</label>
                      					<input type="text" name="title" class="form-input">
                      				</div>

                      				<div class="form-row">
                      					<label for="content">Content</label><br>
                      					<textarea name="content" class="form-input"></textarea>
                      				</div>

                      				<div class="form-row">
                      					<!-- <div class="buddyboss-select-inner"> -->
                      						<select name="privacy" class="form-input">
                      							<!-- <option value="none" selected>Select Privacy</option> -->
                      							<option value="public">Public</option>
                      							<option value="private"selected>Private</option>
                      						</select>
                      					<!-- </div> -->
                      				</div>

                      				<div class="form-row">
                      					<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                      					<input type="submit" name="submit" value="Add Note">
                      				</div>
                      			</form>
                      		</div>
                        </div>

                        <hr>

                        <h2>User Notes</h2>
                      <!-- </div> -->

                    <?php echo do_shortcode('[guw_get_user_notes_custom]'); ?>
                  </div>

                  <div class="tab-content events<?= ( $_GET['current'] == "events" ? ' active' : '' ); ?>">
                      <?php
                        if ( (current_user_can("administrator") || current_user_can("fulfillment_role")) && $user_role == "mentoring_student_role" ){
                          $booking_link = add_query_arg( array(
                            'student_id' => $user_id
                          ), get_permalink(1604) );

                          echo '<a href="'.$booking_link.'" class="button">Book Appointment with Mentor</a>';
                        }
                      ?>
                      <h2>Upcoming Events and Appointments</h2>
                      <?= do_shortcode('[get_recent_events]'); ?>
                  </div>

                <?php endif; ?>

                <?php
                //Give Fulfillment the ability to update a users points
                if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role')) : ?>

                <div class="tab-content fulfillment-tools<?php echo ( $_GET['current'] == "fulfillmenttools" ? ' active' : '' ); ?>">
                  <?php
                    echo '<div class="halfColumn pointFormWrap">';
                    echo '<h2>Add or Subtract from the users Total Points</h2>';
                    echo '<p>Current Points: ' . badgeos_get_users_points($user_id) . '</p>';
                    echo '<form method="post" action="'.site_url().'/index.php?updateuserpoints=1">
                      <div class="form-row">
                        <label for="pointquantity">Update Users Points</label>
                        <input type="number" name="pointquantity" />
                      </div>
                      <input type="hidden" name="action" value="updatepoints">
                      <input type="hidden" name="user_id" value="'.$user_id.'"><div class="form-row-submit"><input type="submit" value="Submit"></div>
                    </form>';
                    echo '</div>';
                  ?>
                </div>

                <?php endif; ?>


                <?php if(current_user_can('fulfillment_role') || current_user_can('administrator') || current_user_can('mentor_role')) : ?>

                <div class="tab-content mentor-tools<?php echo ( $_GET['current'] == "mentortools" ? ' active' : '' ); ?>">
                  <?php
                    echo '<div class="halfColumn pointFormWrap">';
                    echo '<h2>Mentor Tool Form</h2>';
                    echo $mentorForm;
                    echo '</div>';
                  ?>
                </div>

                <?php endif; ?>

                <?php if( current_user_can('fulfillment_role') || current_user_can('administrator') ) : ?>

                <div class="tab-content vip-form<?php echo ( $_GET['current'] == "vipform" ? ' active' : '' ); ?>">
                  <?php
                    echo '<div class="halfColumn pointFormWrap">';
                    echo '<h2>VIP Form</h2>';
                    echo $vipForm;
                    echo '</div>';
                  ?>
                </div>

                <?php endif; ?>

                <?php
                //Give Fulfillment the ability to update a users points
                if(current_user_can('fulfillment_role') || current_user_can('administrator')) : ?>

                <div class="tab-content badges<?php echo ( $_GET['current'] == "badges" ? ' active' : '' ); ?>">
                  <?php
                    echo '<div class="halfColumn pointFormWrap">';
                    echo '<h2>Add Badges to user</h2>';
                    $userBadges = badgeos_get_user_achievements(array('user_id' => $user_id));
                    $userBadgesArr = array();
                    echo '<p>Current Badges: </p>';
                    //var_dump(badgeos_get_user_achievements(array('user_id' => $user_id)));
                    foreach($userBadges as $userBadge){
                      array_push($userBadgesArr, $userBadge->ID);
                      echo '<span class="badge_item">'.badgeos_get_achievement_post_thumbnail( $userBadge->ID, "full", "notsure" ).'</span>';
                    }
                    echo '<form method="post" action="'.admin_url('admin-post.php').'">
                      <div class="form-row">
                        <label for="badge">Select Badge to Apply</label>
                        <select name="badge">';
                        $allAchievements = badgeos_get_achievements(array('post_type' => badgeos_get_achievement_types_slugs(), 'post_status' => 'publish'));
                        foreach($allAchievements as $achievement){
                          if($achievement->post_title !== "" && !in_array($achievement->ID, $userBadgesArr)) {
                            echo '<option value="'.$achievement->ID.'">'.$achievement->post_title.'</option>';
                          }
                        }
                        echo '</select>
                      </div>
                      <input type="hidden" name="action" value="adduserbadge">
                      <input type="hidden" name="user_id" value="'.$user_id.'">
                      <input type="hidden" name="redirect_url" value="'.home_url( add_query_arg( array(), $wp->request ) ).'">
                      <div class="form-row-submit">
                        <input type="submit" value="Submit">
                      </div>
                    </form>';
                    echo '</div>';
                  ?>
                </div>

                <?php endif; ?>

                <?php if( current_user_can('fulfillment_role') || current_user_can('administrator') ) : ?>

                <!--div class="tab-content my_team<?php echo ( $_GET['current'] == "my_team" ? ' active' : '' ); ?>">
                  <?php

                    if ( !current_user_can('mentoring_student_role') && !current_user_can('student_role') && $user_id == $_GET['user_id'] ) {


			                    if ( $user_role == "mentoring_student_role" ){
			                    echo '<h4>Mentor:</h4>'.get_avatar($user_meta['_assignedMentor'][0]).'<p class="profile-info">'.get_user_meta($user_meta['_assignedMentor'][0], 'nickname')[0].'</p><hr><h4>​Support​ Staff:</h4>';
			                    }


			              } else if (!current_user_can('mentoring_student_role') && !current_user_can('student_role') && $user_id == get_current_user_id()) {

			                  if ( $user_role == "mentoring_student_role" ){
			                   echo '<h4>Mentor:</h4>'.get_avatar($user_meta['_assignedMentor'][0]).'<p class="profile-info">'.get_user_meta($user_meta['_assignedMentor'][0], 'nickname')[0].'</p><hr><h4>Support​ Staff:</h4>';
			                  }

			              }
                  ?>
                </div-->

                <?php endif; ?>

                </div>
          </article>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>

  </div>


<?php } else {
    echo '<div class="centerFlex"><h2>You are not allowed to view this student.</h2></div>';
} ?>
 <?php get_footer(); ?>
