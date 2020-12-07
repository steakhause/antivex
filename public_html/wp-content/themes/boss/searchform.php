<?php
/**
 * The template for displaying search forms in Boss
 *
 * @package Boss
 */
?>
<form role="search" method="get" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="search-wrap">
        <label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'boss' ); ?></label>
        <input type="text" value="" name="s" />
        <button type="submit" class="searchsubmit"><i class="fa fa-search"></i></button>
    </div>
</form>			

