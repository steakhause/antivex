<?php
/**
 * Template Name: Multi Family Deal Review Page Template
 *
 * Description: Multi Family Deal Review Page Template
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
if(!memb_hasAnyTags('14214,5562,5560')) { wp_redirect(site_url("dashboard")); exit; }
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
						<div style="display:inline-flex;flex-flow:row wrap;">
	            <h3 style="display:block;width:100%;">This form will help us get an overview of the income/rental property you are considering putting under contract. And it will help us to help you when analyzing and/or structuring the deal. Click the "submit" button at the bottom when you have completed all fields. There will be a delay in our analysis if you submit incomplete answers. NOTE: All fields must be completed.</h3>
	            <h3 style="display:block;width:100%;">To submit a Deal Review, you <strong>MUST</strong> have talked directly with the seller, acquired the basic property info, and made your best guess on the repair estimate if you don't have contractor bids.</h3>
			<h3 style="display: block; width: 100%;"><a href="https://clever-investor-website.s3.amazonaws.com/documents/Landlord-_-Buyer-FlowChart.pdf">Download the LANDLORD+BUYER FLOWSHEET script</a> to help you gather all the information from the seller.</h3>

						</div>
          </div>
            <form method="post" action="<?php echo site_url().'/index.php?mf-deal-review=1'; ?>">

              
              <div class="form-row">
                <h3>What is the full property address, and description of the property?</h3>
								<p>Full address, include beds, baths, sqft, garage, lot size, features, etc.</p>
                <textarea name="property_details"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the exit strategy for this deal? (Required Field)</h3>
								<p>Wholesale, Fix n Flip, Buy and Hold</p>
                <textarea name="exit_strategy"></textarea>
              </div>


              <div class="form-row">
                <h3>How many total units does the property have? How many are currently rented? How many are vacant?</h3>
                <textarea name="current_vacancy"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the total monthly rent / expected monthly rent if currently vacant?</h3>
								<p>If vacant use the expected rents in your calculations.</p>
                <textarea name="monthly_rent"></textarea>
              </div>

              <div class="form-row">
                <h3>What is market rent in the area for similar property/units?</h3>
                <textarea name="market_rent"></textarea>
              </div>

              <div class="form-row">
                <h3>Do the tenants have a month-to-month or long-term lease? When do those leases expire?</h3>
                <textarea name="lease_type"></textarea>
              </div>

              <div class="form-row">
                <h3>Does each unit have separate utility meters?</h3>
                <textarea name="utility_meters"></textarea>
              </div>

              <div class="form-row">
                <h3>Does the landlord pay any of the utilities?</h3>
								<p>If so what utilities and what is the average monthly bill or annual expense?</p>
                <textarea name="landlord_utilities"></textarea>
              </div>

              <div class="form-row">
                <h3>What are the annual taxes, property insurance and any other landlord expenses?</h3>
                <textarea name="annual_expenses"></textarea>
              </div>

              <div class="form-row">
                <h3>What did you find out about the condition of the house? What is the estimate for repairs and renovations on this property to get it into rent-ready condition?</h3>
								<p>Or, already occupied and no immediate repairs needed.</p>
                <textarea name="house_condition"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the seller's asking price? How low do you think you can negotiate with the seller and put it under contract?</h3>
                <p>This is the lowest price the seller said they are willing to sell currently.</p>
                <textarea name="asking_price"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the  motivation and time frame for selling?</h3>sellers
                <textarea name="sellers_motivation"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the estimated Market Value of the property in your opinion?</h3>
                <textarea name="market_value"></textarea>
              </div>

              <div class="form-row">
                <h3>Why do you feel this will make a good deal? Is there any other information you want to share about this deal or local real estate market?</h3>
								<p>Examples: price under market value, terms, seller financing, new development/rezoning nearby, etc</p>
                <textarea name="deal_reason"></textarea>
              </div>

              <!--<div class="form-row">
                <h3>To determine NOI (Net Operating Income)</h3>
              </div>

              <div class="form-row">
                <h3>1. Calculate annual gross income (Monthly Income x 12 months)</h3>
                <textarea name="annual_gross_income"></textarea>
                <h3>2. Subtract the following (3) items<br>
                    - 1 month for vacancy<br>
                    - 1 month for deferred maintenance/ future repairs<br>
                    - Operating expenses (property insurance, property taxes, any landlord paid utilities & property manager) You may see others include expenses for property management, legal and accounting. Do not include mortgage payment/ debt service
                </h3>
                <h3>
                  Net Operating Income is <input name="net_operating_income">, then divide expected by CAP Rate for Area <input name="rate_for_area"> equals <strong>Price / Valuation <input name="price_valuation"> - Any minimal repair costs to get into rent ready condition* <input name="repairs_updates"> - 5k Wholesale Fee = <input name="price"> MAO.
                </h3>
                <h3>* If repairs needed exceed 10 percent of Price, then also run Price / Valuation number thru MAO formula to adjust for extensive repairs before subtracting wholesale fee. </h3>
              </div>

              <div class="form-row">
                <h3>Calculate annual gross income (Monthly Income x 12 months)</h3>
                <textarea name="annual_gross_income"></textarea>
              </div>-->


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
<?php
/**
 * Template Name: Multi Family Deal Review Page Template
 *
 * Description: Multi Family Deal Review Page Template
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
if(!memb_hasAnyTags('14214,5562,5560')) { wp_redirect(site_url("dashboard")); exit; }
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
						<div style="display:inline-flex;flex-flow:row wrap;">
	            <h3 style="display:block;width:100%;">This form will help us get an overview of the income/rental property you are considering putting under contract. And it will help us to help you when analyzing and/or structuring the deal. Click the "submit" button at the bottom when you have completed all fields. There will be a delay in our analysis if you submit incomplete answers.</h3>
	            <h3 style="display:block;width:100%;">To submit a Deal Review, you <strong>MUST</strong> have talked directly with the seller, acquired the basic property info, and made your best guess on the repair estimate if you don't have contractor bids.</h3>
						</div>
          </div>
            <form method="post" action="<?php echo site_url().'/index.php?mf-deal-review=1'; ?>">

              
              <div class="form-row">
                <h3>What is the full property address, and description of the property?</h3>
								<p>Full address, include beds, baths, sqft, garage, lot size, features, etc.</p>
                <textarea name="property_details"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the exit strategy for this deal? (Required Field)</h3>
								<p>Wholesale, Fix n Flip, Buy and Hold</p>
                <textarea name="exit_strategy"></textarea>
              </div>


              <div class="form-row">
                <h3>How many total units does the property have? How many are currently rented? How many are vacant?</h3>
                <textarea name="current_vacancy"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the total monthly rent / expected monthly rent if currently vacant?</h3>
								<p>If vacant use the expected rents in your calculations.</p>
                <textarea name="monthly_rent"></textarea>
              </div>

              <div class="form-row">
                <h3>What is market rent in the area for similar property/units?</h3>
                <textarea name="market_rent"></textarea>
              </div>

              <div class="form-row">
                <h3>Do the tenants have a month-to-month or long-term lease? When do those leases expire?</h3>
                <textarea name="lease_type"></textarea>
              </div>

              <div class="form-row">
                <h3>Does each unit have separate utility meters?</h3>
                <textarea name="utility_meters"></textarea>
              </div>

              <div class="form-row">
                <h3>Does the landlord pay any of the utilities?</h3>
								<p>If so what utilities and what is the average monthly bill or annual expense?</p>
                <textarea name="landlord_utilities"></textarea>
              </div>

              <div class="form-row">
                <h3>What are the annual taxes, property insurance and any other landlord expenses?</h3>
                <textarea name="annual_expenses"></textarea>
              </div>

              <div class="form-row">
                <h3>What did you find out about the condition of the house? What is the estimate for repairs and renovations on this property to get it into rent-ready condition?</h3>
								<p>Or, already occupied and no immediate repairs needed.</p>
                <textarea name="house_condition"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the sellers asking price? How low do you think you can negotiate with the seller and put it under contract?</h3>
                <p>This is the lowest price the seller said they are willing to sell currently.</p>
                <textarea name="asking_price"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the sellers motivation and time frame for selling?</h3>
                <textarea name="sellers_motivation"></textarea>
              </div>

              <div class="form-row">
                <h3>What is the estimated Market Value of the property in your opinion?</h3>
                <textarea name="market_value"></textarea>
              </div>

              <div class="form-row">
                <h3>Why do you feel this will make a good deal? Is there any other information you want to share about this deal or local real estate market?</h3>
								<p>Examples: price under market value, terms, seller financing, new development/rezoning nearby, etc</p>
                <textarea name="deal_reason"></textarea>
              </div>

              <!--<div class="form-row">
                <h3>To determine NOI (Net Operating Income)</h3>
              </div>

              <div class="form-row">
                <h3>1. Calculate annual gross income (Monthly Income x 12 months)</h3>
                <textarea name="annual_gross_income"></textarea>
                <h3>2. Subtract the following (3) items<br>
                    - 1 month for vacancy<br>
                    - 1 month for deferred maintenance/ future repairs<br>
                    - Operating expenses (property insurance, property taxes, any landlord paid utilities & property manager) You may see others include expenses for property management, legal and accounting. Do not include mortgage payment/ debt service
                </h3>
                <h3>
                  Net Operating Income is <input name="net_operating_income">, then divide expected by CAP Rate for Area <input name="rate_for_area"> equals <strong>Price / Valuation <input name="price_valuation"> - Any minimal repair costs to get into rent ready condition* <input name="repairs_updates"> - 5k Wholesale Fee = <input name="price"> MAO.
                </h3>
                <h3>* If repairs needed exceed 10 percent of Price, then also run Price / Valuation number thru MAO formula to adjust for extensive repairs before subtracting wholesale fee. </h3>
              </div>

              <div class="form-row">
                <h3>Calculate annual gross income (Monthly Income x 12 months)</h3>
                <textarea name="annual_gross_income"></textarea>
              </div>-->


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
