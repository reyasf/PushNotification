<?php
/* 
 * Subscription List Search
 */
?>
<div class="push-notification">
    <?= $notification_text; ?>
</div>
<script>
    jQuery(document).ready(function(){
        setTimeout(function(){ jQuery(".push-notification").remove(); }, 30000);
    });
</script>