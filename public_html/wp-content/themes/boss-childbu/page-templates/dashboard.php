<?php
/**
 * Template Name: Dashboard Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 get_header(); 

	if (array_key_exists('user_id', $_GET) && $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }
 

$user_meta = get_user_meta($user_id);

$user_role = guw_get_user_role( $user_id );

$banner_image = get_user_meta( $user_id, 'banner_image', true );

do_shortcode('[memb_sync_contact]');

$level = do_shortcode('[memb_switch]Level[case any_tag=7912]Jumpstart[case any_tag=289]Apprentice[case any_tag=285]Protégé[case any_tag=281]Total Immersion[else]No Level Listed[/memb_switch]');
update_user_meta( $user_id, 'level', $level );

$user_info = wp_get_current_user();
if(empty(get_user_meta($user_info->ID,'email_notificaiton_preference',true))) {
  update_user_meta( get_current_user_id(), 'email_notificaiton_preference', 'Yes' );
}

?>

    <div id="user-settings" class="profile user-settings">

      <div id="primary" class="site-content">
        <div id="content" role="main">


          <article <?php post_class(); ?>>
              <header class="entry-header"<?php if($banner_image) { ?> style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover;"<?php } ?>>

                  <!-- page title -->
                  <div class="profile-image"><?=get_avatar( $user_id, 96 ); ?></div>
                  <h1 class="entry-title">
                  <?php 
                  	if(get_user_meta( $user_id, 'first_name', true ) !='' && get_user_meta( $user_id, 'last_name', true ) !='') {
		                  echo get_user_meta( $user_id, 'first_name', true ).' '.get_user_meta( $user_id, 'last_name', true );
                  	}
                  	else	{
                  		echo ucfirst(get_user_meta( $user_id, 'nickname', true ));
                  	}
                  ?></h1>
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



                </div>                
          </article>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>

 <?php get_footer(); ?>
