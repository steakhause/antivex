<?php

get_header('login');
?>

<style>
.login-container {
	max-width: 400px;
	margin: 10% auto;
  width: 100%;
}

.login-box {
	/* background-color: #fff; */
	color: #fff;
	margin: 15px 0 0;
  text-align: center;
}

.login-box label,
.login-box p{
  color: #fff;
  font-size: 16px;
}

.login-box label{
  margin: 10px 0 0 10px;
  text-align: left;
}

.login-box p.intro{
  font-style: italic;
}

h1 {
	background-image: url('/wp-content/uploads/2017/04/ci_logo.png');
	background-size: contain;
	height: 98px;
	margin: 0 auto;
	text-indent: -9999px;
	width: 95%;
	background-repeat: no-repeat;
}

.login-box input[type="text"], .login-box input[type="password"], .login-box input[type="email"] {
	background-color: #283545;
  height: auto;
	width: 95%;
	padding: 15px;
	font-size: 20px;
	color: #fff;
}

.login-box input[type="submit"] {
	width: 95%;
	margin: 10px 0 0;
}

.forgot-password{
  display: none;
}

#forgot, #forgot-no{
  cursor: pointer;
  margin: 10px 0;
}

#forgot:hover, #forgot-no:hover{
  color: #A1C242;
}
</style>

<script>
  jQuery(document).ready(function(){
    jQuery('#forgot').on('click', function(){
      jQuery('.login').toggle( 'slide' );
      jQuery('.forgot-password').toggle( 'slide' );
    });

    jQuery('#forgot-no').on('click', function(){
      jQuery('.login').toggle( 'slide' );
      jQuery('.forgot-password').toggle( 'slide' );
    });
  });
</script>


<?php

if ( memb_is_loggedin() ){
  wp_redirect( get_permalink( 1028 ) );
}else{
  ?>
  <div class="login-container">
    <h1>Clever Investor</h1>
    <div class="login-box">
      <div class="login">
        <p class="intro">Please login below.</p>
        <?php
          echo do_shortcode('[memb_loginform username_label="Email Address" button_label="Login Now" password_label="Password"]');
        ?>
        <p id="forgot">Forgot Your Password?</p>
        <!-- <p style="margin-top: 25px;">Forgot password? Email <a href="mailto:support@cleverinvestor.com" style="color: #B6D24E">support@cleverinvestor.com</a></p> -->
      </div>
      <div class="forgot-password">
        <p class="intro">Please provide your email below. A link to reset your password will be sent via email.</p>
        <?php
          echo do_shortcode('[memb_send_password]');
         ?>

         <p id="forgot-no">Nevermind</p>
     </div>
   </div>
<?php
}

get_footer('login');

 ?>
