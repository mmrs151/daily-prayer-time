<?php
    class CustomPluginSettings
    {
        public function __construct()
        {
            add_filter( 'plugin_action_links_' . DPT_PLUGIN_FILE , array(&$this, 'dpt_plugin_action_links') );
            add_filter( 'plugin_row_meta', array( &$this, 'dpt_plugin_meta_links'), 10, 2 );

        }

	    /**
	     * Add plugin_action_links
	     */
	    function dpt_plugin_action_links( $links )
	    {
			$settings = array(
				    '<a href="' . admin_url( 'options-general.php?page=dpt' ) . '" title="' . __( 'Daily Prayer Time Settings', 'daily-prayer-time-for-mosques' ) . '">' . __( 'Settings', 'daily-prayer-time-for-mosques') . '</a>'
			);
		    return array_merge($links, $settings);
	    }

	    /**
	     * Add plugin_row_meta links
	     */
	    function dpt_plugin_meta_links( $links, $file ) {

		    $plugin_file = 'svg-support/svg-support.php';

		    if ( $file == DPT_PLUGIN_FILE) {
			    return array_merge(
				    $links,
				    array(
				    '<a target="_blank" href="https://wordpress.org/support/plugin/daily-prayer-time-for-mosques/">' . __( 'Get Support', 'daily-prayer-time-for-mosques') . '</a>',
				    '<a target="_blank" href="https://wordpress.org/support/plugin/daily-prayer-time-for-mosques/reviews/#new-post/">' . __( 'Leave a Review', 'daily-prayer-time-for-mosques' ) . '</a>',
				    '<a target="_blank" href="https://donate.uwt.org/Account/Index.aspx">' . __( 'Support the Ummah', 'daily-prayer-time-for-mosques') . '</a>'
				    )
			    );
		    }

		    return $links;

	    }

    }
