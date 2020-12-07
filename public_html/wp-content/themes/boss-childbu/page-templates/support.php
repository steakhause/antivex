<?php
/**
 * Template Name: Support Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 get_header();
 
 if ( $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }
 

 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

	$banner_image = get_user_meta( $user_id, 'banner_image', true );
	
?>

    <div id="user-settings" class="user-settings">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header" <?php if($banner_image) { ?>  style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover;" <?php } ?>>
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
                ?>

                <?php
                  $user_meta = get_user_meta(get_current_user_id());
                  $contact_fname = do_shortcode('[memb_contact fields="FirstName"]');
                  $contact_email = do_shortcode('[memb_contact fields="Email"]');
                ?>

<?php 
/* Thank you message*/
if(isset($_REQUEST['action']) && $_REQUEST['action'] == 'success') {
	$my_postid = 762;//This is page id or post id
	$content_post = get_post($my_postid);
	$content = $content_post->post_content;
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	echo '<h3 class="formSentMsg" style="color: #008000d6;">'. strip_tags($content).'</h3>';
}
/* TM End*/
?>

                <form accept-charset="UTF-8" action="https://pn120.infusionsoft.com/app/form/process/3bc9c20d87c26920339fa51641a12e37" class="infusion-form" method="POST">
                    <input name="inf_form_xid" type="hidden" value="3bc9c20d87c26920339fa51641a12e37" />
                    <input name="inf_form_name" type="hidden" value="Customer Support&#a;Form submitted" />
                    <input name="infusionsoft_version" type="hidden" value="1.64.0.49" />
                    <input class="infusion-field-input-container" id="inf_field_FirstName" name="inf_field_FirstName" type="hidden" value="<?= $contact_fname; ?>" />
                    <input class="infusion-field-input-container" id="inf_field_Email" name="inf_field_Email" type="hidden" value="<?= $contact_email; ?>" />
                    <div class="infusion-field">
                        <textarea cols="24" id="inf_custom_OLCNotes" name="inf_custom_OLCNotes" rows="5" placeholder="Comment..."></textarea>
                    </div>
                    <div class="infusion-submit">
                        <input type="submit" value="Submit" />
                    </div>
                </form>
                <script type="text/javascript" src="https://pn120.infusionsoft.com/app/webTracking/getTrackingCode"></script>
              </div>

              <footer class="entry-footer">
                  <?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
              </footer>
          </article>
          <?php comments_template( '', true ); ?>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>

 <script>
 
 $(window).load(function(){
 	$(".formSentMsg").delay(3000).slideUp(500);
 });
 </script>

 <?php get_footer(); ?>
