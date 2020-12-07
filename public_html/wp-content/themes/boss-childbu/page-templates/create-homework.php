<?php
/**
 * Template Name: Create Homework Page Template
 *
 * Description: Use this page template for assign homework page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

get_header();

if ( $_GET['homework_id'] ){
	$homework_id = sanitize_text_field( $_GET['homework_id'] );
}

?>

<div class="create-homework">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article <?php post_class(); ?>>
					<header class="entry-header">
							<div class="editEvent"></div>
							<h1 class="entry-title"><?php echo ( $homework_id ? 'Edit Homework' : 'Create Homework' ); ?></h1>
							<div class="editEvent"></div>
					</header>

          <div class="entry-content">
						<?php
						if ( $homework_id ){
							$homework_info = guw_get_single_assignment( $homework_id );
							?>
							<h2>Edit Homework</h2>
	            <form method="post" action="<?php echo site_url().'/index.php?assignments=1'; ?>">
	              <div class="form-row">
	                <label>Homework Title</label>
	                <input type="text" name="homework_title" value="<?php echo $homework_info['title']; ?>">
	              </div><br>

								<div class="form-row">
	                <label>Homework Assignment Information</label>
	                <?php
	                  $settings = array(
	                      'quicktags' => false
	                    );

	                  wp_editor( stripslashes($homework_info['instructions']), "homework_information", $settings );
	                ?>


	              </div>

	              <input type="hidden" name="action" value="edit">
								<input type="hidden" name="assignment_id" value="<?php echo $homework_id; ?>">
	              <input type="submit" value="Save">
							</form>

							<p>This action cannot be undone!</p>
							<form method="post" id="deleteHomework" action="<?php echo site_url().'/index.php?assignments=1'; ?>">
								<input type="hidden" name="action" value="delete">
								<input type="hidden" name="assignment_id" value="<?php echo $homework_id; ?>">
	              <input type="submit" value="Delete Homework">
							</form>
						<?php
						}else{
						 ?>

            <h2>Create Homework</h2>
            <form method="post" action="<?php echo site_url().'/index.php?assignments=1'; ?>">
              <div class="form-row">
                <label>Homework Title</label>
                <input type="text" name="homework_title">
              </div><br>

              <div class="form-row">
                <label>Homework Assignment Information</label>
                <?php
                  $settings = array(
                      'quicktags' => false
                    );

                  wp_editor( "", "homework_information", $settings );
                ?>


              </div>

              <input type="hidden" name="action" value="create">
              <input type="submit" value="Create Homework">
            </form>

						<?php } ?>

          </div>

      </article>
    </div>
  </div>
</div>

<?php
get_footer();

 ?>
