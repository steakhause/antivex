<?php

    /**
     * @package Boss Child Theme
     * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
     * Add your own functions in this file.
     */

    /**
     * Sets up theme defaults
     *
     * @since Boss Child Theme 1.0.0
     */
    function boss_child_theme_setup()
    {
        /**
         * Makes child theme available for translation.
         * Translations can be added into the /languages/ directory.
         * Read more at: http://www.buddyboss.com/tutorials/language-translations/
         */

        // Translate text from the PARENT theme.
        load_theme_textdomain('boss', get_stylesheet_directory() . '/languages');

        // Translate text from the CHILD theme only.
        // Change 'boss' instances in all child theme files to 'boss_child_theme'.
        // load_theme_textdomain( 'boss_child_theme', get_stylesheet_directory() . '/languages' );

    }

    add_action('after_setup_theme', 'boss_child_theme_setup');

    /**
     * Enqueues scripts and styles for child theme front-end.
     *
     * @since Boss Child Theme  1.0.0
     */
    function boss_child_theme_scripts_styles()
    {
        /**
         * Scripts and Styles loaded by the parent theme can be unloaded if needed
         * using wp_deregister_script or wp_deregister_style.
         *
         * See the WordPress Codex for more information about those functions:
         * http://codex.wordpress.org/Function_Reference/wp_deregister_script
         * http://codex.wordpress.org/Function_Reference/wp_deregister_style
         **/
        // wp_enqueue_script( 'script', get_template_directory_uri() . '/script.js', array( 'jquery' ), '1.0.0', true );
        /*
               * Styles
               */
        wp_enqueue_style('boss-child-custom', get_stylesheet_directory_uri() . '/css/custom.css');
        wp_enqueue_style('icomoon', get_stylesheet_directory_uri() . '/fonts/icomoon.css');
        wp_enqueue_style('style', get_stylesheet_directory_uri() . '/style.css', [], '2');
        wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css');
    }

    add_action('wp_enqueue_scripts', 'boss_child_theme_scripts_styles', 9999);

    /****************************** CUSTOM FUNCTIONS *****************************/

    function guw_learndash_profile_custom()
    {

        //include('/home/learncleverinves/public_html/wp-content/plugins/GUW-CleverInvestor/users/profile_template.php');
        include(WP_PLUGIN_DIR . '/GUW-CleverInvestor/users/profile_template.php');

    }

    add_shortcode('guw_ld_profile_custom', 'guw_learndash_profile_custom');

    function guw_get_user_notes_custom()
    {
        if ($_GET['user_id']) {
            $viewing_user = $_GET['user_id'];
        } else {
            $viewing_user = get_current_user_id();
        }
        $page = ($_GET['pagenum']) ? $_GET['pagenum'] : 0;
        $limit = 6;
        $notes = get_notes_by_user($viewing_user, $limit, $page);
        $note_count = get_note_number_by_user($viewing_user);
        $pages = ceil($note_count / $limit);

        echo '<div class="notes-list">';
        if ($notes) {
            echo '<div id="notesWrap" >';
            echo '<h2>Notes</h2>';
            foreach ($notes as $note) {
                if ($note['sender_id'] > 0) {
                        // $sender = get_userdata( $note['sender_id'] );
                        // $sender = $sender->display_name;
                    $sender = "OLC Note";
                } else {
                        // $sender = "Admin";
                    $sender = "OLC Note";
                }

                echo '<div class="note" data-note-privacy="'.$note['private_flag'].'">
                        <h3 class="noteTitle"><span class="icon-file-text2"></span> <span class="noteTitleContent">' . nl2br(stripslashes($note['title'])) . '</span>';
                if (current_user_can("administrator")) {
                    echo ' - <span class="delete-note" data-id="' . $note['id'] . '">Delete</span>';
                }
                if( current_user_can("administrator") || current_user_can("fulfillment_role") || current_user_can("mentor_role") ) {
                  echo ' - <span class="edit-note" data-id="'.$note['id'].'">Edit</span>';
                }
                echo '</h3>';

                echo ' <h3 class="subtext">Posted by ' . $sender . ' on ' . date('F j, Y g:i a T', strtotime($note['date_posted'])) . '</h3>';

                if ($note['private_flag']) {
                    echo '<p class="note-private" style="color: #759430 !important;">Private Note</p>';
                }
                echo '<p class="noteContent">' . nl2br(stripslashes($note['content'])) . '</p>
                    </div>';
            }
            echo '</div>';
        } else {
            echo '<p>There are no notes to display.';
        }
        echo '</div>';



        if ($notes) {
            for ($i = 1; $i <= $pages; $i++) {
                $link = add_query_arg(array(
                    'pagenum' => $i - 1,
                    'user_id' => $viewing_user,
                    'current' => 'notes',
                ), get_permalink());
                echo '<span class="nav-page' . ($i == ($page + 1) ? ' current' : '') . '"><a href="' . $link . '">' . $i . '</a></span>';
            }
        }
    }
    add_shortcode('guw_get_user_notes_custom', 'guw_get_user_notes_custom');

// Add role class to body
    function add_role_to_body1($classes)
    {
        global $current_user;
        $user_role = array_shift($current_user->roles);
        $classes[] = 'role-' . $user_role;
        $classes[] = basename(get_page_template());
        return $classes;
    }

    add_filter('body_class', 'add_role_to_body1');

    function add_role_to_admin_body1($classes)
    {
        global $current_user;
        $user_role = array_shift($current_user->roles);
        $classes .= 'role-' . $user_role;
        return $classes;
    }

    add_filter('admin_body_class', 'add_role_to_admin_body1');

    function guw628_user_last_login($user_login, $user)
    {
        update_user_meta($user->ID, 'last_login_time', time());
    }

    add_action('wp_login', 'guw628_user_last_login', 10, 2);

    add_filter('gettext', 'change_showall_text', 10, 2);
    function change_showall_text($trn, $text)
    {
        if ($text == "Show all") {
            return "All Guides";
        } else {
            return $text;
        }
    }

    /* Redirect to custom login form */
    function goto_login_page()
    {
        /*
        $page = basename($_SERVER['REQUEST_URI']);
        if( ($page == "wp-login.php" || $page == "wp-admin") && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect( get_bloginfo('home') );
        exit;
        }
                 */
    }

    add_action('init', 'goto_login_page');

    add_action('wp_login_failed', 'my_front_end_login_fail');  // hook failed login

    function my_front_end_login_fail($username)
    {
        $referrer = get_bloginfo('home');  // where did the post submission come from?
        // if there's a valid referrer, and it's not the default log-in screen
        if (!empty($referrer) && !strstr($referrer, 'wp-login') && !strstr($referrer, 'wp-admin')) {
            wp_redirect($referrer . '?login=failed');  // let's append some information (login=failed) to the URL for the theme to use
            exit;
        }
    }

    class upsalelinkMetabox
    {
        private $screen = [
            'post',
            'event',
            'document',
            'video_walkthrough',
            'sfwd-courses',
            'sfwd-lessons',
            'sfwd-topic',
            'mt_playlist',
        ];
        private $meta_fields = [
            [
                'label' => 'link',
                'id'    => 'link_41583',
                'type'  => 'text',
            ],
        ];

        public function __construct()
        {
            add_action('add_meta_boxes', [$this, 'add_meta_boxes']);
            add_action('save_post', [$this, 'save_fields']);
        }

        public function add_meta_boxes()
        {
            foreach ($this->screen as $single_screen) {
                add_meta_box('upsalelink', __('Upsale Link', 'textdomain'), [
                    $this,
                    'meta_box_callback',
                ], $single_screen, 'advanced', 'default');
            }
        }

        public function meta_box_callback($post)
        {
            wp_nonce_field('upsalelink_data', 'upsalelink_nonce');
            $this->field_generator($post);
        }

        public function field_generator($post)
        {
            $output = '';
            foreach ($this->meta_fields as $meta_field) {
                $label = '<label for="' . $meta_field['id'] . '">' . $meta_field['label'] . '</label>';
                $meta_value = get_post_meta($post->ID, $meta_field['id'], true);
                if (empty($meta_value)) {
                    $meta_value = $meta_field['default'];
                }
                switch ($meta_field['type']) {
                    default:
                        $input = sprintf('<input %s id="%s" name="%s" type="%s" value="%s">', $meta_field['type'] !== 'color' ?
                            'style="width: 100%"' : '', $meta_field['id'], $meta_field['id'], $meta_field['type'], $meta_value);
                }
                $output .= $this->format_rows($label, $input);
            }
            echo '<table class="form-table"><tbody>' . $output . '</tbody></table>';
        }

        public function format_rows($label, $input)
        {
            return '<tr><th>' . $label . '</th><td>' . $input . '</td></tr>';
        }

        public function save_fields($post_id)
        {
            if (!isset($_POST['upsalelink_nonce'])) return $post_id;
            $nonce = $_POST['upsalelink_nonce'];
            if (!wp_verify_nonce($nonce, 'upsalelink_data')) return $post_id;
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
            foreach ($this->meta_fields as $meta_field) {
                if (isset($_POST[$meta_field['id']])) {
                    switch ($meta_field['type']) {
                        case 'email':
                            $_POST[$meta_field['id']] = sanitize_email($_POST[$meta_field['id']]);
                            break;
                        case 'text':
                            $_POST[$meta_field['id']] = sanitize_text_field($_POST[$meta_field['id']]);
                            break;
                    }
                    update_post_meta($post_id, $meta_field['id'], $_POST[$meta_field['id']]);
                } else if ($meta_field['type'] === 'checkbox') {
                    update_post_meta($post_id, $meta_field['id'], '0');
                }
            }
        }
    }

    if (class_exists('upsalelinkMetabox')) {
        new upsalelinkMetabox;
    };

    function guw_override_search_results($query)
    {
        if (is_admin() || !$query->is_main_query()) {
            return;
        }
        if (is_search()) {
            memb_overrideProhibitedAction("Excerpt");
        }
    }

    add_action('pre_get_posts', 'guw_override_search_results');

    function guw_reset_override_search_results($query)
    {
        if (is_admin()) {
            return;
        }
      
        if (is_search()) {
            memb_overrideProhibitedAction("Hide");
        }
    }

    add_action('parse_query', 'guw_reset_override_search_results');

    function guw_set_main_user_var( $user_login, $user )
    {
        if (!session_id()) {
            session_start();
        }
        $_SESSION['guw_main_user_id'] = $user->ID;
    }
    add_action('wp_login', 'guw_set_main_user_var', 10, 2);

    function guw_set_mentor_user_booking_appointment()
    {
      if(!is_admin()){
        global $post;
        if(is_object($post)){
          if (isset($_GET['student_id']) && $post->ID == 1604) {
              if (isset($_SESSION['guw_main_user_id'])) {
                  //echo '<pre>'.var_dump($_SESSION['guw_main_user_id']).'</pre>';
              }
              $studentId = $_GET['student_id'];
              $current_user = wp_set_current_user($studentId);
  //            wp_set_auth_cookie($studentId);
              return $current_user;
          }
          // else {
          //   $current_user = wp_get_current_user();
          // }
          //return $current_user;
        }
      }
    }
    add_action('pre_get_posts', 'guw_set_mentor_user_booking_appointment');

    function guw_reset_user_after_booking()
    {
        global $current_user;
        if (!session_id()) {
            session_start();
        }
        $redirUrl = site_url( "/profile/" )."?user_id=";
        $userId = $_SESSION['guw_main_user_id'];
        // $dbgLog = fopen(get_home_path() . "dbg.log", "a") or die("Unable to open file!");
        // fwrite($dbgLog, "In AJAX - User Id: ".$userId."\n\n");
        // fclose($dbgLog);
        $current_user = wp_set_current_user($userId);
//        wp_set_auth_cookie($userId);
        echo json_encode(array('success' => true, 'redirect' => $redirUrl));
        exit;
    }
    add_action('wp_ajax_reset_user_after_booking', 'guw_reset_user_after_booking');
    add_action('wp_ajax_nopriv_reset_user_after_booking', 'guw_reset_user_after_booking');

    add_filter('acf/settings/remove_wp_meta_box', '__return_false');

    function add_badgeos_badge_to_user(){
      //echo '<pre>'.var_dump($_POST).'</pre>';
      badgeos_award_achievement_to_user( $_POST['badge'], $_POST['user_id'] );
      wp_redirect($_POST['redirect_url']."?user_id=".$_POST['user_id']);
    }
    add_action('admin_post_adduserbadge', 'add_badgeos_badge_to_user');

    function custom_admin_style() {
      wp_enqueue_style('custom-admin-styles', get_stylesheet_directory_uri().'/admin-custom.css');
    }
    add_action('admin_enqueue_scripts', 'custom_admin_style');

    function guw_apply_user_login_tag( $user_login, $user )
    {
        if(memb_getContactId() !== 0){
          memb_setTags('13912');
        }
    }
    add_action('wp_login', 'guw_apply_user_login_tag', 10, 2);

    function set_login_count_in_is()
    {
      global $i2sdk;
      // $lastLoginDateFV = memb_getContactField('Birthday');
      // $lastLoginDate = new DateTime($lastLoginDateFV);
      // $nowMinusOneDay = new DateTime();
      // $nowMinusOneDay->modify('-1 day');

      $conId = memb_getContactId();
      // if(($nowMinusOneDay > $lastLoginDate || $lastLoginDateFV == "") && $conId != 0){
      if($conId != 0){
        $loginCount = $i2sdk->isdk->dsQueryOrderBy("Contact", 1, 0, array("Id" => $conId), array("_NumberofLogins"), "Id");
        $loginCount = $loginCount[0]["_NumberofLogins"];
        $loginCount = intval($loginCount);
        $loginCount = $loginCount + 1;

        $retVal = memb_setContactField('_NumberofLogins', strval($loginCount));

        $retVal = memb_setContactField('Birthday', date("Y-m-d H:i:s"));
      }
    }
    add_action('set_login_count_in_is', 'set_login_count_in_is');

    function ci_targeted_link_rel($rel_values) {
      return 'noopener';
    }
    add_filter('wp_targeted_link_rel', 'ci_targeted_link_rel',999);

    add_filter('wp_mail_from', 'itsg_mail_from_address');
    function itsg_mail_from_address($email){
        return 'info@cleverinvestor.com';
    }

    add_filter('wp_mail_from_name', 'itsg_mail_from_name');
    function itsg_mail_from_name($from_name){
        return "Clever Investor";
    }

function change_email_message($email_change_mail, $user, $userdata){
    $new_message_txt = __('Hi ###USERNAME###,

This notice confirms that your email address on Clever Investor was changed to ###NEW_EMAIL###.

If you did not change your email address, please contact the Site Administrator at
<a href="mailto:info@cleverinvestor.com">info@cleverinvestor.com</a>

This email has been sent to ###EMAIL###

Regards,
All at ###SITENAME###
###SITEURL###');
    $email_change_mail[ 'message' ] = $new_message_txt;
    return $email_change_mail;
}

// add_filter( 'email_change_email', 'change_email_message', 10, 3 );