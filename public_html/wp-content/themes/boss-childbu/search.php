<?php
if( !is_user_logged_in() ) auth_redirect(); // Requires Login
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

if($_GET['test']) {
    // echo "<pre>";
    // print_r($wp_query);
    // echo "</pre>";
}

get_header();


?>


<style>
/*Temporary Placement: Will move to external styles once complete*/
.results-list li{border-top:1px solid #aaa;list-style:none;margin:0;padding:1em;clear:both;display:flex;flex-direction: row;align-items: center;}
.results-list li:last-of-type{border-bottom:1px solid #aaa;}
.results-list a{text-decoration:underline;}
.results-list p{margin:0 .5em;}
.result-feature.wp-post-image{float:left;margin:0 1em 1em 0;}
</style>
<div id="page-search" class="page-search">
   <div id="primary" class="site-content">
      <div id="content" role="main">
         <article <?php post_class(); ?>>
            <header class="entry-header">
               <!-- page title -->
               <h1 class="entry-title">Search</h1>
            </header>

            <div class="entry-content">
               <div class="doc-search"><?php echo ci_document_search($args); ?></div>
               <!-- <div class="doc-search"><?php //echo ci_search_with_tags($args); ?></div> -->
               <h2>Search result for: <?php echo $_GET['s']; ?></h2>

               <?php
if (have_posts()):
   if($_GET["post_type"]==="document"){
      /*********************/
      /** Document Search **/
      /*********************/

      // Output section header
      echo '<ul class="documentList">
                  <li class="documentLi">
                     <div class="documentName">File Name</div>
                     <div class="documentCategory">Category</div>
                     <div class="documentFrom">From</div>
                     <div class="documentDate">Date</div>
                     <div class="documentDownload">Download</div>'."\n                     ";
      if(current_user_can('fulfillment_role') || current_user_can('administrator')){
         echo '<div class="documentLevel">Membership Level/Tag</div>'."\n                     ";
      }
      echo '</li>'."\n                  ";

      //Output each result in a new LI/row.
      while( have_posts() ) : the_post(); // Foreach post, do:
         // Pretty Var Registry
         $doc_meta    = get_post_meta($post->ID);
         $doc_s3link  = $doc_meta['s3link'][0];
         $doc_userId  = unserialize($doc_meta['userId'][0]);
         $doc_IStags  = $doc_meta['IStags'][0];
         $doc_title   = $post->post_title;
         $doc_content = $post->post_content;
         $doc_author  = get_user_meta($post->post_author,'nickname',true); // E.N. - changed from: "get_user_meta($post->post_author)['nickname'][0]"
         $doc_date    = date('m/d/Y', strtotime($post->post_date));
		 $doc_catlist = get_the_terms($post->ID,'group')[0]->name;

         // If admin add edit icon, otherwise leave blank.
         $editLink = (current_user_can('administrator')) ? '<a href="/edit-documents?documentId=' . $post->ID . '" target="_blank"><i class="fa fa-pencil-square-o"></i></a>' : '';

         if(in_array(get_current_user_id(), $doc_userId) || memb_hasAnyTags($doc_IStags)){
            echo '<li class="documentLi">
                     <div class="documentName">
                        '. $editLink .'<span class="icon-file-text2"></span>
                        '. $doc_title .'
                        <span class="icon-question"></span><span class="documentTip">'.strip_tags($doc_content).'</span>
                     </div>
                     <div class="documentCategory">'. $doc_catlist .'</div>
                     <div class="documentFrom">'. $doc_author .'</div>
                     <div class="documentDate">'. $doc_date .'</div>
                     <div class="documentDownload"><a href="'. $doc_s3link .'"><span class="icon-download2"></span></a></div>'."\n                     ";
		    if(current_user_can('fulfillment_role') || current_user_can('administrator')){
		       $membershipLevels = do_shortcode('[memb_tag_name tagids="'. $doc_IStags .'" delimiter="," separator=" | "]');
		       echo '<div class="documentLevel">'. $membershipLevels .'</div>'."\n                     ";
		    }
            echo '</li>'."\n                  ";
         } // if(in_array(...) || memb_hasAnyTags(...))
      endwhile; // while( have_posts() )
   } // if($_GET["post_type"]==="document")

   else{ // Show default search result format
      /********************/
      /** Regular Search **/
      /********************/

      // Function: Creates an excerpt for [custom-type] posts that do not have an explicit excerpt set.
      function autoExcerpt($result_id){
          // Get the post's body/content.
          $result_content = strip_tags(apply_filters('the_content', get_post_field('post_content', $result_id)));
          // Many video posts have an (odd) space-comma-space bit appended after it. This removes it from the excerpt.
		  $result_content = preg_replace('/^(\s|&nbsp;)*,(\s|&nbsp;)*/','',$result_content,1);
          // Truncate if necessary.
          if(strlen($result_content) < 160)
             return $result_content;
          else
		     return substr($result_content,0,160).'... ( <a href="'.get_permalink($result_id).'">Read more</a> )';
      }

      // Begin search results section
      echo '<ul class="results-list">'."\n                  ";

      while( have_posts() ) : the_post(); // Foreach post, do:
         if($post->post_status == 'draft') {
             continue;
         }
         // Register Pretty Vars
         $post_url     = get_permalink($post->ID);
    		 $type_icon    = ''; // Eventually we'll set at the beginning of each switch($post->post_type) case for dynamic filetype icons to appear next to title
    		 $post_img     = get_the_post_thumbnail(null, 'medium', [ 'class' => 'result-feature' ] );
    		 $post_excerpt = (! empty($post->post_excerpt)) ? strip_tags($post->post_excerpt) : autoExcerpt($post->ID);

             $post_meta    = get_post_meta($post->ID);
    		 $post_userId  = unserialize($post_meta['userId'][0]);
    		 $post_IStags  = $post_meta['IStags'][0];
             $ldAccessTags = $post_meta['_is4wp_access_tags'];
             $upsale_link = $post_meta['link_41583'][0];
             if($_GET['ajdhsj']) {
                 echo "<pre>";
                 print_r($post->post_status);
                 echo "</pre>";
             }
    		 $user_hasAccess = ( in_array( get_current_user_id(), $post_userId ) || memb_hasAnyTags($post_IStags) || memb_hasAnyTags($ldAccessTags) );

         if($post->post_type !== "document"){
            // Format and output non-document results into a new LI/row like so:
               echo '<li id="post-' . $post->ID . '" class="result-li">
                        '.$post_img.'
                        <div class="result-name">
                           '. $type_icon;
            if($user_hasAccess){ // Members get a working link to the resource, the title of the resource and an excerpt of the resource
               echo '<a class="result-title" href="'. $post_url .'">'. $post->post_title .'</a>
                           <p class="result-excerpt">'. $post_excerpt .'</p>'."\n                        ";
            }else{ // Non-members just get the title (and probably a link to upgrade membership)
               if($upsale_link){
               echo $post->post_title.'
                       <p class="result-excerpt">This content is currently locked. <a href="' . $upsale_link . '"  target="_blank">Click here to learn more.</a></p>';
			   }else{
					echo $post->post_title.'
                       <p class="result-excerpt">This content is currently locked. Request access to view this content.</p>';
			   }
            }
            echo '</div>
                    </li>'."\n                  ";
         }else{// Format and output Document-typed results into a new LI/row like so:
            $post_author  = get_user_meta($post->post_author,'nickname',true); // E.N. - changed from: "get_user_meta($post->post_author)['nickname'][0]"
            $post_date    = date('m/d/Y', strtotime($post->post_date));
            $post_s3link  = $post_meta['s3link'][0];
			$type_icon    = '<span class="icon-file-text2"></span>';

            $editLink = (current_user_can('administrator')) ? '<a title="Edit File" target="_blank" href="/edit-documents?documentId=' . $post->ID . '"><i class="fa fa-pencil-square-o"></i></a>' : '';

            echo '<li class="documentLi">
                     '.$post_img.'
                     <div class="documentName">
                        '. $editLink .' '.$type_icon."\n                        ";
            if($user_hasAccess)
               echo'<a href="'. $post_s3link .'">'. $post->post_title .'</a>';
            else
               echo $post->post_title;
            echo' by <i>'. $post_author .'</i> on '. $post_date."\n                     ";
			if($user_hasAccess){
               echo '<div class="documentDownload" style="display:inline-block; float:right;"><a href="'. $post_s3link .'"><span class="icon-download2"></span> Download File</a></div>
                        <p class="documentTip">'.$post_excerpt.'</p>
                     </div>'."\n                     ";
            } // if(user_hasAccess)
            else{
				 if($upsale_link){
					echo $post->post_title.'
                       <p class="result-excerpt">This content is currently locked. <a href="' . $upsale_link . '"  target="_blank">Click here to learn more.</a></p>\n                     ';
			   }else{
					echo $post->post_title.'
                       <p class="result-excerpt">This content is currently locked. Request access to view this content.</p>\n                     ';
			   }
            }
		    if(current_user_can('fulfillment_role') || current_user_can('administrator')){
		       $membershipLevels = do_shortcode('[memb_tag_name tagids="'. $post_IStags .'" delimiter="," separator=" | "]');
		       echo '<div class="documentLevel">'. $membershipLevels .'</div>';
		    }
            echo '</li>'."\n                  ";
         }



      endwhile; // while( have_posts() )

   } // end else block to if($_GET["post_type"]==="document") ?>

                  <div class="pagination-below">
<?php                 buddyboss_pagination(); ?>
                  </div>
               </ul>

<?php
// END: if(have_posts())
else: // Show "No Results Found" message.
?>

   <div class="search-content">
      <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'boss'); ?></p>
   </div><!-- .search-content -->

<?php endif; // END: else block to if(have_posts()) ?>
            </div><!-- .entry-content -->

            <footer class="entry-footer">
<?php edit_post_link(__('Edit', 'boss'), '<span class="edit-link">', '</span>'); ?>
            </footer>
         </article>
<?php comments_template('', true); ?>
      </div><!-- #content -->
   </div><!-- #primary -->
</div><!-- #page-search -->
<?php get_footer(); ?>
