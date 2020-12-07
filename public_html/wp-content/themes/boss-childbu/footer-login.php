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

<div id="ci-alert" class="ci-alert-box"></div>

<footer id="colophon" role="contentinfo">


</footer><!-- #colophon -->


<?php wp_footer(); ?>
<script>
$(document).ready(function(){
	$('.memberium-login-error').replaceWith('<div id="login_error" style="background: #fff; color: #2f2f2f; padding: 15px; width: 94.5%; margin: 15px auto; border-left: 5px solid red;"><strong>ERROR</strong>: The username or password<br>you entered is incorrect.</div>')
	
});
</script>
</body>
</html>
