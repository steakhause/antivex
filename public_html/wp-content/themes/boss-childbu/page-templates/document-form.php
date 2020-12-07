<?php
/**
 * Template Name: Document Form Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 get_header();
?>

<?php
  global $i2sdk;
  $app = $i2sdk->isdk;

  $userMeta = get_user_meta(get_current_user_id());
  $user = wp_get_current_user();
  $document_id = sanitize_text_field( $_GET['documentId'] );
  $document_meta = get_post_meta( $document_id );

  echo '<pre>'; print_r($document_meta); echo '</pre>';
  echo $document_meta['title'];
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
                ?>

                <div class="entry-form">
                  <form id="document-form" action="<?php echo get_site_url(); ?>/wp-content/themes/boss-child/document-redirect.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="senderId" value="<?php echo get_current_user_id(); ?>">
                    <ul class="editWindow">
                      <li class="form-title form-row">
                    		<label for="form-title">Title</label><br />
                    		<input id="form-title" class="form-input" name="name" type="text" <?php // echo $document_meta['title'] ? 'value="' $document_meta['title'] : 'value=""' ); ?> /><br />
                    	</li>

                      <li class="form-category form-row">
                        <label for="form-category">Document Group</label><br />
                        <select id="form-category" class="form-input" name="category[]" multiple="multiple">
                          <?php
                            $taxonomies = get_terms(array('taxonomy' => 'group', 'hide_empty' => false));

                            // echo '<option value="None" selected>Select a Group</option>';

                            foreach ( $taxonomies as $taxonomy ) {
                              echo '<option value="' . $taxonomy->term_id . '">' . $taxonomy->name . '</option>';
                            }
                          ?>
            						</select>
                                    <p>Hold down the Ctrl (windows) / Command (Mac) button to select multiple options.</p>
                      </li>

                      <li class="form-file form-row">
                    		<label for="file">Upload File</label><br />
                    		<input id="form-file" class="form-input" name="file" type="file" <?php // echo $document_meta['title'] ? 'value="' $document_meta['title'] : 'value=""' ); ?> /><br />
                    	</li>

                    	<li class="form-description form-row">
                    		<label for="form-message">Description</label><br />
                    		<textarea id="form-message" class="form-input" name="description"></textarea><br />
                    	</li>

                      <?php if ( current_user_can("fulfillment_role") || current_user_can('administrator') ) {

                        echo '<div id="dropDownContainer">
                          <li class="form-membership form-row">
                            <label for="form-membership">Membership Level</label><br />
                            <select id="form-membership" class="form-input" name="membership">';

                        			// $levels = get_infusionsoft_tag_ids_by_category_id(136, $app);
                        			$levels = get_infusionsoft_tag_ids_by_category_id(132, $app);
                              if ( $levels ){
                                echo '<option value="None" selected>Select a Membership Level</option>';
                                foreach ( $levels as $level ) {
                                  echo '<option value="' . $level['Id'] . '">' . $level['GroupName'] . '</option>';
                                }
                              }
                						echo '</select><i class="fa fa-plus-circle membership-span" aria-hidden="true"></i>
                            <input type="hidden" class="membVals" name="membershipLevel">
                          </li>';
                          ?>

                          <?php echo '<li id="mentorByTag" class="mentorByTag form-infusionsoft form-row">
                            <label for="eventMentor">Infusionsoft Tag Category</label><br />
                            <select id="eventMentor" class="eventMentorTag eventMentor form-input" name="infusionsoftTags">';

                              // Get an array of mentors with their Infusionsoft ID as the keys
                              $tag_list = get_infusionsoft_tag_ids_by_category_id(126, $app);
                              if ( $tag_list ) {
                                echo '<option value="None" selected>Select a Tag</option>';
                                foreach ( $tag_list as $tags ) {
                                  echo '<option value="' . $tags['Id'] . '">' . $tags['GroupName'] . '</option>';
                                }
                              }
                            echo '</select><i class="fa fa-plus-circle membership-span" aria-hidden="true"></i>

                            <input type="hidden" class="membVals" name="infusionsoftTags">
                        </li>
                      </div>

                      <div id="tagContainer"><p>Memberships and Tags</p><ul></ul></div>';

                      } ?>

                      <li class="form-row">
                        <label for="form-checkbox">Select Recipients</label><br />
                        <?php
                          if ( current_user_can("fulfillment_role") || current_user_can('administrator') ) {
                            echo '<input type="checkbox" name="allCheck" id="allCheck" class="allCheck" value=""><span class="selectAllGroups">Select All</span><br />';
                          }
                            $mentorList = get_users(array('role'=>'mentor_role'));
                            $studentList = get_users(array('role'=>'mentoring_student_role'));

                          if ( $mentorList ) {
                            foreach ( $mentorList as $mentorKeys => $mentors ) {
                              if ( current_user_can("fulfillment_role") || $mentors->data->ID == get_current_user_id() || current_user_can('administrator') ) {
                                echo '<div class="displayAllMentorsAndStudents">';
                                  if ( current_user_can("fulfillment_role") || current_user_can('administrator') ) {
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
                                  }
                                  echo '<input type="checkbox" name="groupCheck" class="groupCheck" value=""><i>Select All in this Group</i><br />';
                                echo '</div>';
                              }
                            }
                          }
                        ?>
                      </li>

                      <input type="hidden" name="documentId" value="<?php echo $document_id; ?>">

                    	<input id="form-submit" type="submit" value="Submit" />
                    </ul>
                  </form>
                </div>

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
