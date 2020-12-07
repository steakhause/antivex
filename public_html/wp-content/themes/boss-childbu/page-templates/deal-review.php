<?php
/**
 * Template Name: Deal Review Page Template
 *
 * Description: Use this page template for audio calls page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
if(!memb_hasAnyTags('14214,5562,5560,5564')) { wp_redirect(site_url("dashboard")); exit; }
get_header();

?>

<style>
.deal-review input[type="submit"]{
	width: 100%;
}

.deal-review textarea {
	border: 2px solid #4dcadd;
	background: #fff;
	font-size: 15px;
	line-height: 22px;
	/*color: #4dcadd !important;*/
	width: 100%;
	height: 40px;
}

.deal-review textarea {
	height: 150px;
}

.deal-review h3 {
	font-size: 18px;
	line-height: 1;
}

.deal-review p {
	margin: 0 0 10px;
}

</style>

<div class="deal-review">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<article <?php post_class(); ?>>
					<header class="entry-header">
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
            <form method="post" action="<?php echo site_url().'/index.php?deal-review=1'; ?>">

              <div class="form-row">
                <h3>What is the full property address, and description of the property? (Required Field)</h3>
								<p>Full address, include beds, baths, sqft, garage, lot size, features, etc.</p>
                <textarea name="property_description"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the exit strategy for this deal?</h3>
								<p>Wholesale, fix n flip, RSS, etc?</p>
                <textarea name="property_plan"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the seller's motivation and time frame for selling?</h3>
								<p>10 days, 1 month, etc.</p>
                <textarea name="backup_plan"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the seller's asking price? How low do you think you can negotiate with the seller and put it under contract?</h3>
								<p>This is the lowest price the seller said they are willing to sell at currently.</p>
                <textarea name="lowest_price"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the retail value? (After Repaired Value/ARV)</h3>
								<p>If you don't know, what does Zillow show for the area?</p>
                <textarea name="retail_value"></textarea>
              </div>

              <div class="form-row">
                <h3>What did you find out about the condition of the house?  What is the estimate for repairs and renovations on this property?</h3>
								<p>Guideline to help with estimating repairs:<br>
                  A rental property may only be a few thousand to be rent ready.<br>
                  $10.00  per Sqft for light updating<br>
                  $20.00  per Sqft for moderate rehab<br>
                  $30.00+ per Sqft for full gut rehab<br>
                </p>
                <textarea name="repairs_needed"></textarea>
              </div>

              <div class="form-row">
                <h3>Why do you feel this will make a good deal? Is there any other information you want to share about this deal or local real estate market?</h3>
								<p>Examples: price under market value, terms, seller financing, new development/rezoning nearby, etc</p>
                <textarea name="good_deal"></textarea>
              </div>

              <div class="form-row">
                <h3>List 3 comparable SOLD properties nearby.</h3>
								<p>Include # bedrooms, # baths, garage, square footage, and price.</p>
                <textarea name="comparable_properties"></textarea>
              </div>

              <input type="submit" name="submit" value="Submit Deal for Review">
            </form>

          </div>

      </article>
    </div>
  </div>
</div>

<?php
get_footer();

?>
