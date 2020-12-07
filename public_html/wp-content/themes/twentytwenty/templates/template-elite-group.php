<?php
/**
 * Template Name: Elite Group
 * Template Post Type: post, page
 *
 * @package WordPress
 * @subpackage Twenty_Twenty
 * @since Twenty Twenty 1.0
 */

get_header();
?>

    <h1>Elite Group Management Utility</h1>
    <div class="form_wrapper">
        <form id="pod_tag_form">
            <div class="form_row">
                <label for="pod_tag">Retreive Members of</label>
                <select id="pod_tag" name="pod_tag">
                    <?php include "get_tags.php" ?>
                </select>
            </div>
            <button type="submit" id="pod_tag_submit" value="Submit">Submit</button>
        </form>
    </div>
    <div id="table_target"></div>
    <div id="pod_end_date_form_target"></div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
<?php
get_footer();
?>