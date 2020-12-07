<?php
echo '
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<div style="text-align:center;">
<button id="webinar_button" type="button" data-webinarHash="q7773i6" style="border: 2px solid rgba(0, 0, 0, 0.5); background: rgba(41, 182, 246, 0.95); color: rgb(255, 255, 255); font-size: 24px; padding: 18px 80px; box-shadow: none; border-radius: 4px; white-space: normal; font-weight: 700; line-height: 1.3; cursor: pointer; font-family: Roboto, -apple-system, BlinkMacSystemFont, &quot;Segoe UI&quot;, Roboto, &quot;Helvetica Neue&quot;, Arial, sans-serif; word-break: break-word; margin: auto;">Register now</button>
<script src="https://event.webinarjam.com/register/q7773i6/embed-button"></script>
<script type="text/javascript">
$(document).ready(function() {
    jQuery(".webinar_button").click(function() {
        jQuery("#wj_registration_frame").ready(function() {
            do {
                var src = jQuery("#wj_registration_frame").attr("src");
                jQuery("#wj_registration_frame").attr("src", src + window.location.search.replace("?", "&"));
            }
            while(jQuery("#wj_registration_frame").attr("src") == "")
        });
    });
});
</script>

';
?>
