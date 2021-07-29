<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/admin/partials
 */

settings_errors(); ?>
<div class="wrap">
    <h1 class="wp-heading-inline">Breaking News</h1>
    <form method="post" action="options.php">
        <div class="bn-settings-content">
			<?php
			do_action( 'bn_settings_content' );
			?>
        </div>
		<?php submit_button(); ?>
    </form>
</div>