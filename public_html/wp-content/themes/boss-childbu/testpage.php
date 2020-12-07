<?php
/**
 * Template Name: Notification test page
 *
 */

if(isset($_POST['test'])){
    $args = array( 'content' => 'This is a test notification', 'sender_id' => 1, 'user_id' => array(1), 'link' => get_permalink(762),'test'=>'test' );
    $r = send_notification($args);
    highlight_string("<?php\n\n\$data = " . var_export($r, true) . ";\n\n? >");
    
} else {

get_header();

?>

<?php if ( is_active_sidebar('sidebar') ) : ?>
    <div class="page-right-sidebar">
<?php else : ?>
    <div class="page-full-width">
<?php endif; ?>
        <div id="primary" class="site-content">

            <div id="content" role="main">
            <h1>This is a test of the notification system</h1>
            <div id="notification_test"></div>
            <input id="numn" type="number" value="1"/>
            <div id="notification_test_button">test</div>
        <script>
            (function($){
                $('#notification_test_button').on('click',function(){
                    $.post('?test',{test:$('#numn').val()},function(r){
                        $('#notification_test').html('');
                        $('#notification_test').html(r);
                        console.log(r);
                    });
                });
            })(jQuery);
        </script>
        <style>
            #notification_test_button{
                border: 1px solid #434343 !important;
                background: rgba(0, 0, 0, 0) none repeat scroll 0 0 !important;
                height: 45px;
                width:150px;
                text-align:center;
                display:flex;flex-flow:column;
                align-items:center;
                justify-content:center;
                color: #434343 !important;
            }
        </style>
        </div><!-- #content -->
        </div><!-- #primary -->

    <?php if ( is_active_sidebar('sidebar') ) :
        // get_sidebar('sidebar'); 
    endif; ?>
    </div>
<?php get_footer(); } ?>
