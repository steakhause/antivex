<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */  
 if ( $_GET['user_id'] ) {
   $user_id = $_GET['user_id'];
 } else {
   $user_id = get_current_user_id();
 }
 

 $user_meta = get_user_meta($user_id);

 $user_role = guw_get_user_role( $user_id );

	$banner_image = get_user_meta( $user_id, 'banner_image', true );
?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
<?php $parent = wp_get_post_parent_id( get_the_ID() ); ?>
		<?php if ( $parent == '5521' ) { ?>
			<header class="entry-header <?php if(is_search()){ echo 'page-header'; }?>" style="background-color: transparent; background: #fff;">
				<!-- <h1 class="entry-title <?php if(is_search()){ echo 'main-title'; }?>"><?php the_title(); ?></h1> -->
				<img src="https://learn.cleverinvestor.com/wp-content/uploads/2018/01/crypto-alliance-guide_logo.png" alt="site logo">
				<span style=" font-family: 'Raleway', sans-serif; font-size: 26px; line-height: 62px; margin-left: 5px; font-weight: 200; color: #363636 !important;">ALLIANCE <?php echo strtoupper(get_the_title()); ?></span>
			</header>
		<?php } else {  ?>

			<header class="entry-header <?php if(is_search()){ echo 'page-header'; }?>" <?php if($banner_image){?>style="background: url('<?php echo $banner_image; ?>') no-repeat right center / cover;"<?php } ?>>
				<h1 class="entry-title <?php if(is_search()){ echo 'main-title'; }?>"><?php the_title(); ?></h1>
			</header>
		<?php }  ?>
		<div class="entry-content">
			<?php the_content(); ?>
			<?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'boss' ), 'after' => '</div>' ) ); ?>
		</div><!-- .entry-content -->

		<footer class="entry-meta">
			<?php edit_post_link( __( 'Edit', 'boss' ), '<span class="edit-link">', '</span>' ); ?>
		</footer><!-- .entry-meta -->

	</article><!-- #post -->
