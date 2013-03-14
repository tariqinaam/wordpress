<?php

/*
 * Hide the admin notice update.
 */
function hide_update_notice() {
    remove_action( 'admin_notices', 'update_nag', 3 );
}