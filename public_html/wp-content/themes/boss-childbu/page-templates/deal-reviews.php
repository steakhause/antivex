<?php
/**
 * Template Name: Deal Reviews Page Template
 *
 * Description: Use this page template for audio calls page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
if(!memb_hasAnyTags('14214,14271,5562,5560,5564')) { wp_redirect(site_url("dashboard")); exit; }
get_header();

if ( $_GET['student'] ){
	$viewing_user = sanitize_text_field( $_GET['student'] );
	$view_all = true;
}else{
	$viewing_user = get_current_user_id();
	$view_all = true;
}

if ( $_GET['deal'] ){
  $viewing_deal = sanitize_text_field( $_GET['deal'] );
  $view_all = false;
}

if( $_GET['deal_type'] ){
	$viewing_type = sanitize_text_field( $_GET['deal_type'] );
	$view_all = false;
}

$student_id =  $_SESSION['$user_id'];

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

<div class="deal-review" >

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article <?php post_class(); ?>>
			<?php if($student_id) { ?>
					<header class="entry-header profile-banner-on-courses-page" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
              <!-- page title -->
              <div class="profile-image"><?=get_avatar( $student_id, 96 ); ?></div>
              <h1 class="entry-title"><?php echo get_user_meta( $student_id, 'first_name', true ).' '.get_user_meta( $student_id, 'last_name', true ); ?></h1>
              <div class="profile-badges">
                <?php
                  $badges = badgeos_get_user_achievements( array( 'user_id' => $student_id ) );

                  if ( $badges ){
                    foreach ( $badges as $badge ){
                      echo badgeos_get_achievement_post_thumbnail( $badge->ID );
                    }
                  }
                 ?>
              </div>

              <div class="message_btn">
                <?php if ( get_current_user_id() != $student_id ){
                  echo '<a href="'.site_url().'/messages/?fepaction=newmessage&to='.$student_id.'"><button>Message</button></a>';
                } ?>
              </div>
          </header>
			<?php } ?>
					<header class="entry-header" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
							<div class="editEvent"></div>
							<h1 class="entry-title"><?php the_title(); ?></h1>
							<div class="editEvent"></div>
					</header>

          <div class="entry-content">
          <div class="deal-review-instruction">
            <h3 style="display:inline-flex; width:35%;">Be sure to watch this training on how to comp your properties. It's important when submitting a Deal Review that you are providing the best comps and details about the comps.</h3>
            <div id="deal-review-instuction-video" style="width:60%; display:inline-flex; float:right;">
              <iframe src="https://player.vimeo.com/video/274951646" width="640" height="360" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
            <?php
							if ( current_user_can("mentoring_student_role") && get_current_user_id() != $viewing_user ){
								exit_with_footer("You do not have permission to view this user's deal reviews.");
							}

							if ( current_user_can("mentor_role") && !is_assigned_to( $viewing_user )  && get_current_user_id() != $viewing_user ){
								exit_with_footer("You do not have permission to view this user's deal reviews.");
							}

              if ( $view_all ){
                $deals = get_deal_reviews_by_user( $viewing_user );
                if ( $deals ){
                  echo '<h3>Single Family Deal Reviews</h3><ul class="documentList">';
                    echo '<li class="documentLi"><div class="reviewDate">Date</div><div class="reviewLink">Link</div></li>';
                    foreach ( $deals as $deal ){
                      $link = add_query_arg( array(
                        'deal' => $deal['id'],
												'deal_type' => 'single',
                      ), get_permalink() );

                      echo '<li class="documentLi">
                        <div class="reviewDate"><span class="icon-file-text2"></span> '. stripslashes(substr($deal['property_description'], 0, 25)) . '&mldr; on '.$deal['date_posted'].'</div>
                        <div class="reviewLink"><a href="'.$link.'">VIEW</a></div>
                      </li>';
                    }
                    echo '</ul>';
                }

								$deals = get_mf_deal_reviews_by_user( $viewing_user );
                if ( $deals ){
                  echo '<h3>Income Producing/Rental Deal Reviews</h3><ul class="documentList">';
                    echo '<li class="documentLi"><div class="reviewDate">Date</div><div class="reviewLink">Link</div></li>';
                    foreach ( $deals as $deal ){
                      $link = add_query_arg( array(
                        'deal' => $deal['id'],
												'deal_type' => 'multi',
                      ), get_permalink() );

                      echo '<li class="documentLi">
                        <div class="reviewDate"><span class="icon-file-text2"></span> '. stripslashes(substr($deal['property_description'], 0, 25)) . '&mldr; on '.$deal['date_posted'].'</div>
                        <div class="reviewLink"><a href="'.$link.'">VIEW</a></div>
                      </li>';
                    }
                    echo '</ul>';
                }
              }else{
								if (isset($viewing_type) && $viewing_type == "multi") {
									$deal = get_mf_deal_review( $viewing_deal );
								} else {
									$deal = get_deal_review( $viewing_deal );
								}

                $getContactId = get_user_meta($deal[0]['user_id'], 'infusionsoft_user_id', true);

                $conFirstName = do_shortcode('[memb_contact contact_id="' . $getContactId . '" fields="FirstName"]');
                $conLastName = do_shortcode('[memb_contact contact_id="' . $getContactId . '" fields="LastName"]');
                echo "<h5>Submitted By: <span class='lightWeight'>$conFirstName $conLastName</span><br />";
                echo 'Submitted On: <span class="lightWeight">' . date('jS F Y', strtotime($deal[0]['date_posted'])) . '</span></h5>';

								if (isset($viewing_type) && $viewing_type == "multi") { ?>
									<div class="form-row">
											<h3>What is the full property address, and description of the property?</h3>
											<p> <?php echo stripslashes($deal[0]['property_details']); ?></p>
									</div>

									<div class="form-row">
												<h3>What is the exit strategy for this deal?</h3>
												<p><?php echo stripslashes($deal[0]['exit_strategy']); ?></p>
									</div>

									<div class="form-row">
												<h3>How many total units does the property have? How many are currently rented? How many are vacant?</h3>
												<p> <?php echo stripslashes($deal[0]['current_vacancy']); ?></p>
									</div>

									<div class="form-row">
											<h3>What is the total monthly rent / expected monthly rent if currently vacant?</h3>
											<p><?php echo stripslashes($deal[0]['monthly_rent']); ?></p>
									</div>

									<div class="form-row">
											<h3>What is Market Rent in the area for a similar property/units?</h3>
											<?php echo stripslashes($deal[0]['market_rent']); ?></p>
									</div>

									<div class="form-row">
											<h3>Do the tenants have a month-to-month or long-term lease? When do those leases expire?</h3>
											<p><?php echo stripslashes($deal[0]['lease_type']); ?></p>
									</div>

									<div class="form-row">
											<h3>Does each unit have separate utility meters?</h3>
											<p><?php echo stripslashes($deal[0]['utility_meters']); ?></p>
									</div>

									<div class="form-row">
											<h3>Does the landlord pay any of the utilities?</h3>
											<p><?php echo stripslashes($deal[0]['landlord_utilities']); ?></p>
									</div>

									<div class="form-row">
											<h3>What are the annual taxes, property insurance and any other landlord expenses?</h3>
											<p><?php echo stripslashes($deal[0]['annual_expenses']); ?></p>
									</div>

									<div class="form-row">
											<h3>What did you find out about the condition of the house? What is the estimate for repairs and renovations on this property to get it into rent-ready condition?</h3>
											<p><?php echo stripslashes($deal[0]['house_condition']); ?></p>
									</div>

									<div class="form-row">
											<h3>What is the sellers asking price? How low do you think you can negotiate with the seller and put it under contract?</h3>
											<p><?php echo stripslashes($deal[0]['asking_price']); ?></p>
									</div>

									<div class="form-row">
											<h3>What is the sellers motivation and time frame for selling?</h3>
											<p><?php echo stripslashes($deal[0]['sellers_motivation']); ?></p>
									</div>

									<div class="form-row">
											<h3>What is the estimated Market Value of the property in your opinion?</h3>
											<p><?php echo stripslashes($deal[0]['market_value']); ?></p>
									</div>

									<div class="form-row">
											<h3>Why do you feel this will make a good deal? Is there any other information you want to share about this deal or local real estate market?</h3>
											<p><?php echo stripslashes($deal[0]['deal_reason']); ?></p>
									</div>

									 <!-- <div class="form-row">
							                  <h3>Annual Gross Income</h3>
							                  <p><?php // echo stripslashes($deal[0]['annual_gross_income']); ?></p>
									</div> -->

									 <!--<div class="form-row">
							                  <h3>Net Operating Income</h3>
							                  <p><?php // echo stripslashes($deal[0]['net_operating_income']); ?></p>
									</div>

									 <div class="form-row">
							                  <h3>CAP Rate for Area</h3>
							                  <p><?php // echo stripslashes($deal[0]['rate_for_area']); ?></p>
									</div>

									 <div class="form-row">
							                  <h3>Repairs / Updates</h3>
							                  <p><?php // echo stripslashes($deal[0]['repairs_updates']); ?></p>
									</div>

									 <div class="form-row">
							                  <h3>Price / Valuation</h3>
							                  <p><?php // echo stripslashes($deal[0]['price_valuation']); ?></p>
									</div>

									 <div class="form-row">
							                  <h3> MAO</h3>
							                  <p><?php echo stripslashes($deal[0]['price']); ?></p>
									</div>-->

								<?php } else { ?>
    						<div class="form-row">
                  <h3>What is the full property address, and description of the property? (Required Field)</h3>
                  <p><?php echo stripslashes($deal[0]['property_description']); ?></p>
								</div>

    						<div class="form-row">
                  <h3>What is the exit strategy for this deal?</h3>
                 <p> <?php echo stripslashes($deal[0]['property_plan']); ?></p>
							 </div>

    			 			<div class="form-row">
                  <h3>What is the sellers motivation and time frame for selling?</h3>
                 <p> <?php echo stripslashes($deal[0]['backup_plan']); ?></p>
						 	</div>

							<div class="form-row">
                  <h3>What is the sellers asking price? How low do you think you can negotiate with the seller and put it under contract?</h3>
                  <p><?php echo stripslashes($deal[0]['lowest_price']); ?></p>
							</div>

							<div class="form-row">
                  <h3>What is the retail value? (After Repaired Value/ARV)</h3>
                  <?php echo stripslashes($deal[0]['retail_value']); ?></p>
							</div>

							<div class="form-row">
                  <h3>What did you find out about the condition of the house?  What is the estimate for repairs and renovations on this property?</h3>
                  <p><?php echo stripslashes($deal[0]['repairs_needed']); ?></p>
								</div>

		 					<div class="form-row">
                  <h3>Why do you feel this will make a good deal? Is there any other information you want to share about this deal or local real estate market?</h3>
                  <p><?php echo stripslashes($deal[0]['good_deal']); ?></p>
								</div>

		 					<div class="form-row">
                  <h3>List 3 comparable SOLD properties nearby.</h3>
                  <p><?php echo stripslashes($deal[0]['comparable_properties']); ?></p>
								</div>

							<?php } ?>

                  <div class="comments">
                    <h2>Comments</h2>
										<?php if (isset($viewing_type) && $viewing_type == "multi") { ?>
											<form method="post" action="<?php echo site_url().'/index.php?mf-comments=1'; ?>">
										<?php } elseif (isset($viewing_type) && $viewing_type == "single") { ?>
											<form method="post" action="<?php echo site_url().'/index.php?comments=1'; ?>">
										<?php } else { ?>
											<form method="post" action="<?php echo site_url().'/index.php?comments=1'; ?>">
										<?php } ?>
                      <textarea name="comment"></textarea>
											<input type="checkbox" name="notify" value="1" checked> Notify everyone in deal review process?<br><br>
                      <input type="hidden" name="deal_id" value="<?php echo $viewing_deal; ?>">
                      <input type="submit" name="submit" value="Add a Comment">
                    </form>

                    <?php
											if (isset($viewing_type) && $viewing_type == "multi") {
												$comments = get_mf_deal_review_comments( $viewing_deal );
											} elseif (isset($viewing_type) && $viewing_type == "single") {
												$comments = get_deal_review_comments( $viewing_deal );
											} else {
												$comments = get_deal_review_comments( $viewing_deal );
											}

                      if ( $comments ){
												$i = 1;
                        foreach ( $comments as $comment ){
													$class = ( $i % 2 == 0 ? ' even' : ' odd' );
                          $commenter = get_userdata( $comment['user_id'] );
													$profile_link = add_query_arg( array(
															'user_id' => $comment['user_id']
														), get_permalink(819) );

                          echo '<div class="comment'.$class.'">
														<div class="user-info">
															<a href="'.$profile_link.'">' . get_avatar( $comment['user_id'], 48) . '<br>
															'.$commenter->display_name.'</a>
														</div>
														<div class="comment-content">
															'.stripslashes(nl2br($comment['comment'])).'
															<p class="date-posted">'.date( 'n/d/Y g:i a', strtotime( $comment['date_posted'] ) ).'</p>
														</div>
													</div>';
													$i++;
                        }
                      }
                     ?>
                  </div>
              <?php }
             ?>
          </div>

      </article>
    </div>
  </div>
</div>

<?php
get_footer();

 ?>
