<?php
/**
 * Template Name: Documents Page Template
 *
 * Description: Use this page template for events page.
 *
 * @package WordPress
 * @subpackage Boss
 * @since Boss 1.0.0
 */

 get_header();
 
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

    <div id="page-documents" class="page-documents">

      <div id="primary" class="site-content">
        <div id="content" role="main">

          <article <?php post_class(); ?>>
              <header class="entry-header" <?php if($banner_image) { ?>  style="background-image: url('<?php echo $banner_image; ?>');" <?php } ?>>
                  <!-- page title -->
                  <div class="editEvent"></div>
                  <h1 class="entry-title"><?php the_title(); ?></h1>
                  <div class="editEvent"></div>
              </header>

              <?php
              if (current_user_can('fulfillment_role') || current_user_can('administrator')) {
                  echo '<div class="entry-content">';
              } else {
                  echo '<div class="entry-content shortWrap">';
              } ?>

                <div class="doc-search"><?php echo ci_document_search($args); ?></div>
                
                <?php if($_GET[test]):?>
					
                    <div class="doc-search"><?php echo ci_search_with_tags($args); ?></div>
                
                <?php endif;?>

              <?php
                // Get page content if there is any to allow for an editable description.
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
                        the_content();
                    }
                }
              ?>

              <?php // $documents = get_documents();
//current_user_can('fulfillment_role') ||
              echo '<ul class="documentList">';

              if (current_user_can('fulfillment_role') || current_user_can('administrator')) {
                  echo '<li class="documentLi"><div class="documentName">File Name</div><div class="documentCategory">Category</div><div class="documentFrom">From</div><div class="documentDate">Date</div><div class="documentDownload">Download</div><div class="documentLevel">Membership Level/Tag</div></li>';
              } else {
                  echo '<style>.documentList li.documentLi .documentName{ width: 30%;}</style><li class="documentLi"><div class="documentName">File Name</div><div class="documentDownload">Download</div></li>';
              }

              $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
              if (current_user_can('fulfillment_role') || current_user_can('administrator')) {
                      $args = array(
                        'post_type' => 'Document',
                        'posts_per_page' => 10,
                        'order' => 'DESC',
                        'paged' => $paged,
                      );
              } else {
                      $tagList = explode(',', $_SESSION['memb_user']['Groups']);

                      $memberTags = [
                          'relation' => 'OR',
                          array(
                              'key' => 'userId',
                              'value' => serialize(strval(get_current_user_id())),
                              'compare' => 'LIKE'
                          ) ];

                      foreach ($tagList as $tag) {
                          array_push($memberTags, array(
                              'key' => 'IStags',
                              'value' => $tag,
                              'compare' => 'LIKE'
                          ));
                      }



                      $args = array(
                        'post_type' => 'Document',
                        'posts_per_page' => 10,
                        'order' => 'DESC',
                        'paged' => $paged,
                        'meta_query' => $memberTags
                      );
              }

                  $the_query = new WP_Query($args);

                  $documentCount = 0;

                  if ($the_query->have_posts()) {
                      while ($the_query->have_posts()) {
                          $the_query->the_post();

                          $meta_data = get_post_meta(get_the_ID());

                          $documents[get_the_ID()] = array(
                            'title' => get_the_title(),
                            'address' => get_permalink(),
                            'description' => get_the_content(),
                            'from' => get_the_author(),
                            'date' => get_the_date(),
                            's3link' => $meta_data['s3link'][0],
                            'userId' => $meta_data['userId'][0],
                            'IStags' => $meta_data['IStags'][0],
                          );
                      }

                      $pageCount = 0;

                      if ($documents) {
                          foreach ($documents as $Keys => $document) { 
                              $tags = $document['IStags'];
                              $userId = unserialize($document['userId']);

                              if ( current_user_can('fulfillment_role') || current_user_can('administrator') ) {
                                  $membershipLevels = do_shortcode('[memb_tag_name tagids="' . $document['IStags'] . '" delimiter="," separator=" | "]');

                                  $editLink = '';
                                  if(current_user_can('administrator')) {
                                      $editLink = '<a href="/edit-documents?documentId=' . $Keys . '" target="_blank"><i class="fa fa-pencil-square-o"></i></a>';
                                  }

                                  if (in_array(get_current_user_id(), $userId) || memb_hasAnyTags($tags)) {
                                      $documentCount++;
                                      echo '<li class="documentLi">
            										<div class="documentName">' . $editLink . '<span class="icon-file-text2"></span> '.$document['title'].'<span class="icon-question"></span><span class="documentTip">'.strip_tags($document['description']).'</span></div>
                										<div class="documentCategory">'.strip_tags(get_the_term_list($Keys, 'group', '', ', ')).'</div>
                                    <div class="documentFrom">'.$document['from'].'</div>
                                    <div class="documentDate">'.$document['date'].'</div>
                                    <div class="documentDownload"><a href="'.$document['s3link'].'"><span class="icon-download2"></span></a></div><div class="documentLevel">' . $membershipLevels . '</div>
                                  </li>';
                                  }
                              } else {

                                  if (in_array(get_current_user_id(), $userId) || memb_hasAnyTags($tags)) {
                                      $documentCount++;
                                      echo '<li class="documentLi">
                										<div class="documentName"><span class="icon-file-text2"></span> '.$document['title'].'<span class="icon-question"></span><span class="documentTip">'.strip_tags($document['description']).'</span></div>
                										<!-- <div class="documentCategory">'.strip_tags(get_the_term_list($Keys, 'group', '', ', ')).'</div>
                                                        <div class="documentFrom">'.$document['from'].'</div>
                                                        <div class="documentDate">'.$document['date'].'</div> -->
                                    <div class="documentDownload"><a href="'.$document['s3link'].'"><span class="icon-download2"></span></a></div>
                                  </li>';
                                  }
                              }
                          } // END foreach ($documents as $Keys => $document) {

                          $big = 9999999;

                          echo paginate_links(array(
                            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                            'format' => '?paged=%#%',
                            'current' => max(1, get_query_var('paged')),
                            'total' => $the_query->max_num_pages
                        ));

                          wp_reset_postdata();

                          //   $limit = 3;
                    //   $pages = ($documentCount) ? ceil( $documentCount / $limit ) : 0;
                    //   $page = ( isset($_GET['pagenum']) ) ? $_GET['pagenum'] : 0;
                      //
                    //   if($documentCount) {
                    //       echo '<div class="paginationWrap">';
                    //       for( $i = 1; $i <= $pages; $i++ ){
                    //           $link = add_query_arg( array(
                    //               'pagenum' => $i - 1
                    //           ), get_permalink() );
                    //           echo '<span class="nav-page'.(($i == ($page+1)) ? ' current' : '').'"><a href="'.$link.'">'.$i.'</a></span>';
                    //       }
                    //       echo '</div>';
                      // }
                      } else {
                          echo '<br><div>You have no documents.</div>';
                      }
                  
                }
              echo '</ul>';
              ?>
              </div>

              <footer class="entry-footer">
                  <?php edit_post_link(__('Edit', 'boss'), '<span class="edit-link">', '</span>'); ?>
              </footer>
          </article>
          <?php comments_template('', true); ?>

        </div><!-- #content -->
      </div><!-- #primary -->

    </div>



 <?php get_footer(); ?>
