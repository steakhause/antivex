<?php
/**
 * Template Name: Notifications Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 $userId = get_current_user_id();
 
 if ( $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }
 

 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

	$banner_image = get_user_meta( $user_id, 'banner_image', true );

 global $wpdb;
 $dbquery = $wpdb->prepare( "SELECT notifications.id, notifications.content, notifications.date, notifications.sender_id, assigned.notification_id, assigned.user_id, assigned.status FROM `ci_notifications` AS notifications, `ci_assigned_notifications` AS assigned WHERE assigned.user_id = %d AND notifications.id = assigned.notification_id ORDER BY notifications.date DESC", $userId );
 $wpquery = $wpdb->get_results($dbquery);

 get_header();
?>
    <!-- uncheck all the checkboxes on page load -->
    <script>
      jQuery(document).ready( function() {
        var ins = document.getElementsByTagName('input');
        for (var i=0; i<ins.length; i++) {
          if (ins[i].getAttribute('type') == 'checkbox') { ins[i].checked = false; }
        }
      });
    </script>

    <div id="Notifications" class="notificaitons">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header" <?php if($banner_image) { ?>   style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover;" <?php } ?>>
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

                if ( current_user_can("fulfillment_role") || current_user_can("administrator") || current_user_can("mentor_role") ) {
                  echo '<div class="newNotification"><a href="'.get_permalink(451).'">Send Group Notification >>></a></div>';
                }

                // form to build the notifications for the user
                echo '<form id="formNotification" action="'.get_site_url().'/wp-content/themes/boss-child/delete-notification-redirect.php" method="post">';
                  echo '<input type="hidden" name="userId" value="'.$userId.'"><br>';
                  echo '<div class="notificationRow notificationRowHeader"><input type="checkbox" name="selectAll" value="selectAll" id="selectAll" class="checkNotification"><div class="notificationHeaderWrapper"><div class="notificationFrom">From</div><div class="notificationDate">Date</div><div class="notificationContent">Notification</div></div></div>';
                  // if the query yields value, build the notification
                  if ( $wpquery ) {
                    foreach ($wpquery as $key => $notifications) {
                      // notification in bold for 'unread', unbold and italic for 'read'
                      $messageStyles = ( $notifications->status=='unread' ) ? 'font-weight:700;font-style:normal' : 'font-weight:300;font-style:italic';
                      $sender = get_user_meta($notifications->sender_id)['nickname'][0];
                      $date = date("m/d/Y", strtotime($notifications->date));
                      $content = $notifications->content;

                      $stripedContent = filter_var($content, FILTER_SANITIZE_STRING);
                      $filteredContent = htmlspecialchars($content);

                      echo '<div class="notificationRow">';
                        echo '<input type="checkbox" name="notification[]" class="checkNotification" value="'.$notifications->id.'">';
                        echo '<div class="notificationRowContent" style="' . $messageStyles . '" data-sender="'.$sender.'" data-date="'.$date.'" data-content="'.$filteredContent.'">';
                          echo '<div class="notificationFrom">'.$sender.'</div>';
                          echo '<div class="notificationDate">'.$date.'</div>';
                          echo '<div class="notificationContent">'.$stripedContent.'</div>';
                        echo '</div>';
                      echo '</div>';
                    }
                  // if the query does not yield any result, output message
                  } else {
                    echo '<br><div>You have no notifications</div>';
                  }
                  // display the delete button only if there is a result from the query
                  if ( $wpquery ) {
                  echo '<br><div class="notificationDeleteWrapper"><div class="notificationDelete">DELETE</div></div>';
                }
                echo '</form>';
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
