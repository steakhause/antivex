<?php
global $rtl;
$header_style = boss_get_option('boss_header');
$boxed = boss_get_option( 'boss_layout_style' );
?>

<?php if( '1' == $header_style ) { ?>
<div class="middle-col">
<?php } ?>
	<?php
    $buddypanel_menu = '';
	if ( $boxed == 'boxed' ) {
		// <!-- Custom menu -->
		$buddypanel_menu = wp_nav_menu( array(
			'theme_location' => 'left-panel-menu',
			'items_wrap'	 => '%3$s',
			'fallback_cb'	 => '',
			'container'		 => false,
			'echo'			 => false,
			'walker'		 => new BuddybossWalker
		) );
	}

	$titlebar_menu = wp_nav_menu( array(
		'theme_location' => 'header-menu',
		'items_wrap'	 => '%3$s',
		'fallback_cb'	 => '',
		'echo'			 => false,
		'container'		 => false,
		'walker'		 => new BuddybossWalker
	) );

	if ( !empty( $buddypanel_menu ) || !empty( $titlebar_menu ) ): ?>
		<!-- Navigation -->
		<div class="header-navigation">
			<div id="header-menu">
				<ul>
					<?php echo $buddypanel_menu . $titlebar_menu; ?>
				</ul>
			</div>
			<a href="#" class="responsive_btn"><i class="fa fa-align-justify"></i></a>
		</div>
    <?php else: ?>
        <div class="header-navigation">
            <p></p>
        </div>
    <?php endif; ?>

    <?php if( '2' == $header_style ): ?>

    <div id="titlebar-search">
    
    	<a href="#" id="search-open" class="header-button" title="<?php _e( 'Search', 'boss' ); ?>"><i class="fa fa-search"></i></a>

    	<?php echo ci_global_search(); ?>
		
   		  <!-- <?php //get_template_part( 'searchform', 'header' ); ?> -->

		 <!-- <a href="#" id="search-open" class="header-button" title="<?php //_e( 'Search', 'boss' ); ?>"><i class="fa fa-search"></i></a> -->
        
    </div>

    <?php else: ?>

	<?php if ( $boxed == 'boxed' ) { ?>
		<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<input type="text" value="" name="s" id="s" placeholder="<?php _e( 'Type to search...', 'boss' ); ?>">
            <input type="hidden" name="post_status" id="post_status" value="publish" />
			<button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
		</form>
    <?php } ?>

    <?php endif; ?>

<?php if( '1' == $header_style ) { ?>
</div><!--.middle-col-->
<?php } ?>

<script type="text/javascript">
	
	jQuery(document).ready(function() {

		// function myFunction() {
			var sel = document.querySelector('#select-group');
			// var option_type = this.document.querySelector("option");
			
			sel.addEventListener('change', function() {
				// var type = document.querySelector("option").getAttribute("type");

				var type = this.options[this.selectedIndex].getAttribute("type");
				console.log(type);
				// if the option type = group, then change the select tag name = group;
				// if the option type = category, then change the select tag name = category;

					if (type == "category") {
						sel.name = "Category";
						// document.getElementById("#select-group").name = "Category";
						console.log("see category");
					} else {
						sel.name = "Group";
						// document.getElementById("#select-group").name = "Group";
						console.log("see group");
					}

				});
			// }

		// ("#select-group").addEventListener("change", myFunction());

		

		

	}); 
</script>








