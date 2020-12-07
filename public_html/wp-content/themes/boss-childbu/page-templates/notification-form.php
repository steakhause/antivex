<?php
/**
 * Template Name: Notification Form Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 $userMeta = get_user_meta(get_current_user_id());

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

    <div id="user-settings" class="user-settings">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header">
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

                // email preference for notification checkbox. Pre-load it as checked/unchecked based on previously saved
                echo '<form class="emailNotificationPreference" action="'.get_site_url().'/wp-content/themes/boss-child/group-notification-redirect.php" method="post">
                  <input type="hidden" name="senderId" value="'.get_current_user_id().'">
                  <h4>Please enter the content of the notification:</h4>
                  <textarea class="notificationContent" name="notificationContent"></textarea><br /><br />
                  <h4>Please select the recepients of the notification:</h4>';
                  if ( current_user_can("fulfillment_role") ) {
                    echo '<input type="checkbox" name="allCheck" id="allCheck" class="allCheck" value=""><span class="selectAllGroups">Select All</span><br />';
                  }
                    $mentorList = get_users(array('role'=>'mentor_role'));
                    $studentList = get_users(array('role'=>'mentoring_student_role'));

                    if ( $mentorList ) {
                      foreach ( $mentorList as $mentorKeys => $mentors ) {
                        if ( current_user_can("fulfillment_role") || current_user_can("administrator") || $mentors->data->ID == get_current_user_id() ) {
                          echo '<div class="displayAllMentorsAndStudents">';
                            if ( current_user_can("fulfillment_role") || current_user_can("administrator") ) {
                              echo '<div class="allCheckContainer"><input type="checkbox" class="mentorCheck eachCheck" name="notification[]" value="'.$mentors->data->ID.'">'.$mentors->data->display_name.'</div>';
                            }
                            if ( $studentList ) {
                              echo '<ul class="displayAllMentoringStudents">';
                              foreach ( $studentList as $studentKeys => $students ) {
                                if (get_user_meta($students->data->ID)['_assignedMentor'][0] == $mentors->data->ID) {
                                  echo '<li><input type="checkbox" class="studentCheck eachCheck" name="notification[]" value="'.$students->data->ID.'">'.$students->data->display_name.'</li>';
                                }
                              }
                              echo '</ul>';
                              // echo '<input type="checkbox" name="mentorCheck" class="mentorCheck" value=">Select All in the Group<br />';
                            }
                            echo '<input type="checkbox" name="groupCheck" class="groupCheck" value=""><i>Select All in this Group</i><br />';
                          echo '</div>';
                        }
                      }
                    }
                    echo '<br /><input type="submit" value="Send Notification">
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

  </div>

 <?php get_footer(); ?>
