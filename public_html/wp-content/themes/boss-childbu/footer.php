<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */
?>
</div><!-- #main .wrapper -->

</div><!-- #page -->

</div> <!-- #inner-wrap -->

</div><!-- #main-wrap (Wrap For Mobile) -->

<div id="ci-alert" class="ci-alert-box"></div>

<footer id="colophon" role="contentinfo">

	<?php get_template_part( 'template-parts/footer-widgets' ); ?>

	<div class="footer-inner-bottom">

		<div class="footer-inner">
			<?php get_template_part( 'template-parts/footer-copyright' ); ?>
		<div  class="termbar">
 <?php echo wp_nav_menu( array( 'menu' => 'Bottom Footer Navigation', 'container' => '', 'menu_class' => 'nav navbar-nav customnav') ); ?>

<style> .termbar{  clear: both;
    text-align: center;
    padding: 10px 0px;}
	.termbar ul {     margin: 0 auto;}
	.termbar ul li{ display: inline-block;}
	.termbar ul li:after{ content: '|';  color: #727272;}
	.termbar ul li:last-child:after{ display: none;}
	.termbar ul li a{     padding: 0 10px;    color: #727272;}
	.termbar ul li a:hover{ text-decoration: underline; color: #000;}</style>
</div>
			<?php get_template_part( 'template-parts/footer-links' ); ?>
		</div><!-- .footer-inner -->

	</div><!-- .footer-inner-bottom -->

	<?php do_action( 'bp_footer' ) ?>

</footer><!-- #colophon -->
</div><!-- #right-panel-inner -->
</div><!-- #right-panel -->

</div><!-- #panels -->

<div class="alertOverlay">
  <div class="alertBox">
		<div class="notificationHeader">Alert</div>
		<div class="notificationContainer">
	    <h4>Do you really want to delete the event?</h4>
	    <p><span>Cancel</span><a href="<?php echo get_site_url(); ?>/wp-content/themes/boss-child/delete-redirect.php?postId=<?php echo get_the_ID(); ?>">Confirm</a></p>
		</div>
  </div>
</div>

<div class="notificationOverlay">
  <div class="notificationBox">
		<div class="notificationHeader">Alert</div>
		<div class="notificationContainer">
	    <h4>Do you really want to delete the notification(s)?</h4>
	    <p><span class="span1">Cancel</span><span class="span2">Confirm</span></p>
		</div>
  </div>
</div>

<div class="readNotificationOverlay">
  <div class="readNotificationBox">
		<div class="notificationHeader">Notification</div>
		<div class="notificationContainer">
			<p class="p1">From: <span></span></p>
			<p class="p2">Date: <span></span></p>
			<p class="p3">Notification: <span></span></p>
	    <h5><span>Ok</span></h5>
		</div>
  </div>
</div>

<div class="deleteOverlay">
  <div class="deleteBox">
		<div class="notificationHeader">Alert</div>
		<div class="notificationContainer">
	    <h4></h4>
	    <p><span id="cancel">Cancel</span><span id="confirm">Confirm</span></p>
		</div>
  </div>
</div>



<?php wp_footer(); 


 global $current_user;

     $user_roles = $current_user->roles;

	if (in_array("mentoring_student_role", $user_roles))
  {
?>
<style>#header-menu .notificationhide{ display: inline-block !important; }</style>
<?php } ?>

<script>
$(document).ready(function(){
	$('form.wpua-edit p.submit').append("<span> Click to save your Avatar</span>");
	$('.notificationBubble').attr('href',$('.notificationBubble').prev('a').attr('href'));
});

$(window).load(function(){
if($('#header-menu ul').find('li.hideshow ul li').length == 0){
	$('#header-menu ul').find('li.hideshow').css('opacity','0');
}
});

$(window).resize(function(){
if($('#header-menu ul').find('li.hideshow ul li').length == 0){
	$('#header-menu ul').find('li.hideshow').css('opacity','0');
}
});
//launchpad guide duel submit buttons
$("#post-25124 #sfwd-mark-complete, #post-25127 #sfwd-mark-complete").hide();
submitGoalsForm = function(){
    document.getElementById("inf_form_328b8052614da5a255c43c9ab2a37635").submit();
    document.getElementById("sfwd-mark-complete").submit();
}
//Technical Setup Guide Submit
submitTechnicalSetupForm = function(){
	$("button,#learndash_next_prev_link").hide();
	$("#inf_form_e1bdb733e2bdcf88ce18f25b742f5a9e").slideUp("slow", function(){
		$("#launchpad_guide_completed").fadeIn(function(){
			// document.getElementById("sfwd-mark-complete").submit();
			document.getElementById("inf_form_e1bdb733e2bdcf88ce18f25b742f5a9e").submit();
		    
		})
	})
}
</script>
<link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400" rel="stylesheet">
</body>
</html>
