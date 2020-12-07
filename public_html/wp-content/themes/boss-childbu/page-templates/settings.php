<?php
/**
 * Template Name: Settings Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
get_header();
 $userMeta = get_user_meta(get_current_user_id());

 if (isset( $_POST['submit_my_image_upload'] )){
		if($_POST['my_image_upload_nonce']) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
			$attachment_id = media_handle_upload( 'my_image_upload', $_POST['post_id'] );
			if ( is_wp_error( $attachment_id ) ) {
				echo "<script>alert('No Image is selected');</script>";
			}
			else {
			$banner_meta_val = wp_get_attachment_image_url($attachment_id, 'full');
				update_user_meta( get_current_user_id(), 'banner_image', $banner_meta_val);
			}
		}
		else {
			echo "<script>alert('No Image is selected');</script>";
		}
	}

$banner_image = get_user_meta(get_current_user_id(), 'banner_image', true);

?>

    <div id="user-settings" class="user-settings">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header" <?php if($banner_image){ ?> style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover;"<?php } ?>>
                  <!-- page title -->
                  <div class="editEvent"></div>
                  <h1 class="entry-title"><?php the_title(); ?></h1>
                  <div class="editEvent"></div>
              </header>

              <div class="entry-content">
                <?php
                // Get page content if there is any to allow for an editable description.
        				if ( have_posts() ) {
        					while ( have_posts() ) {
        						the_post();
        						the_content();
        					}
        				}

                echo '<br /><br />';

                // change email and password functions
                // if(!current_user_can( 'fulfillment_role' )) {
                //     echo '<h3>Change your Email</h3>';
                //     echo do_shortcode( '[memb_change_email]' );
                // }

                echo '<h3>Change Your Avatar</h3>';

                echo do_shortcode('[avatar_upload]');

		                echo '<br />';
		echo '<hr /><br />';
                echo '<h3>Change Your Profile Banner</h3><p id="wpua-upload-messages-existing">
        <span id="wpua-max-upload-existing" class="description">Profile Banner image should be 1245 X 160</span>
      </p>';
      echo '<p>Current Banner Image:<br>'.$banner_image.'</p>';

                // echo do_shortcode('[guw_upload_profile_banner]');
                ?>

                <form id="featured_upload" method="post" action="#" enctype="multipart/form-data">
									<input type="file" name="my_image_upload" id="my_image_upload"  multiple="false" />
									<input type="hidden" name="post_id" id="post_id" value="0" />
									<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
									<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" />
								</form>


                <?php
								    echo '<br />';
		echo '<hr /><br />';
                echo '<h3>Manage Your Password</h3>';
                // echo do_shortcode( '[memb_update_form success_url="/"]<p>First Name: <input type="text" required="required" name="FirstName"></p><p>Last Name: <input type="text" required="required" name="LastName"></p><p>Email: <input type="text" required="required" name="Email"></p><p><input type="submit"></p>[/memb_update_form]' );
                echo do_shortcode( '[memb_change_password]' );


                echo '<br />';

                // email preference for notification checkbox. Pre-load it as checked/unchecked based on previously saved
                $checkbox = ( $userMeta['email_notificaiton_preference'][0]=='Yes' ) ? 'checked' : '' ;

                echo '<hr /><br />';
                echo '<h3>Manage Your Email</h3>';

                echo '<p>Upon email update, you will be logged out and required to log back in with your new email address</p>';

                echo do_shortcode( '[memb_change_email email1label="Enter Email Address" email2label="Enter your Email Address again" buttontext="Change my Email" actionset_id="18466"]');

                echo '<br/>';

                echo '<hr/><br/>';

                echo '<h3>Manage Your Email Preference</h3>';
                echo '<form class="emailNotificationPreference" action="'.get_site_url().'/wp-content/themes/boss-child/settings-redirect.php" method="post">
                  <input type="hidden" name="userId" value="'.get_current_user_id().'">
                  <input type="checkbox" name="emailPreference" id="emailPreference" class="emailPreference" value="Yes" '.$checkbox.'>Check to get emails sent for notifications<br />
                  <input type="submit" value="Save Preference">
                </form>';
                ?>
              </div>

              <footer class="entry-footer">
                  <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
              </footer>
          </article>
          <?php comments_template( '', true ); ?>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>


 <?php get_footer(); ?>
