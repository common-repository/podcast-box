<?php

/**
 * Plugin Name: Podcast Box
 * Plugin URI:  https://wpmilitary.com/podcast-box
 * Description: Worldwide Podcasts Directory.
 * Version:     1.0.2
 * Author:      WP Military
 * Author URI:  https://wpmilitary.com/
 * Text Domain: podcast-box
 * Domain Path: /languages/
 *
 */
// don't call the file directly
if ( !defined( 'ABSPATH' ) ) {
    wp_die( __( 'You can\'t access this page', 'podcast-box' ) );
}

if ( function_exists( 'pb_fs' ) ) {
    pb_fs()->set_basename( false, __FILE__ );
} else {
    // DO NOT REMOVE THIS IF, IT IS ESSENTIAL FOR THE `function_exists` CALL ABOVE TO PROPERLY WORK.
    
    if ( !function_exists( 'pb_fs' ) ) {
        // ... Freemius integration snippet ...
        // Create a helper function for easy SDK access.
        function pb_fs()
        {
            global  $pb_fs ;
            
            if ( !isset( $pb_fs ) ) {
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/freemius/start.php';
                $pb_fs = fs_dynamic_init( array(
                    'id'             => '8530',
                    'slug'           => 'podcast-box',
                    'premium_slug'   => 'podcast-box-pro',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_6d8056ade0f8637b5470b55fb4f91',
                    'is_premium'     => false,
                    'premium_suffix' => 'PRO',
                    'has_addons'     => false,
                    'has_paid_plans' => true,
                    'menu'           => array(
                    'slug'       => 'edit.php?post_type=podcast',
                    'first-path' => 'edit.php?post_type=podcast&page=podcast-box-get-started',
                    'contact'    => false,
                    'support'    => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $pb_fs;
        }
        
        // Init Freemius.
        pb_fs();
        // Signal that SDK was initiated.
        do_action( 'pb_fs_loaded' );
    }
    
    // ... Your plugin's main file logic ...
    /** define constants */
    define( 'PODCAST_BOX_VERSION', '1.0.2' );
    define( 'PODCAST_BOX_FILE', __FILE__ );
    define( 'PODCAST_BOX_PATH', dirname( PODCAST_BOX_FILE ) );
    define( 'PODCAST_BOX_INCLUDES', PODCAST_BOX_PATH . '/includes' );
    define( 'PODCAST_BOX_URL', plugins_url( '', PODCAST_BOX_FILE ) );
    define( 'PODCAST_BOX_ASSETS', PODCAST_BOX_URL . '/assets' );
    define( 'PODCAST_BOX_TEMPLATES', PODCAST_BOX_PATH . '/templates' );
    define( 'PODCAST_BOX_PRICING', admin_url( 'edit.php?post_type=podcast&page=podcast-box-pricing' ) );
    //Register activation hook of the plugin
    register_activation_hook( PODCAST_BOX_FILE, function () {
        include_once PODCAST_BOX_INCLUDES . '/class-install.php';
        Podcast_Box_Install::activate();
    } );
    //Register deactivation hook of the plugin
    register_deactivation_hook( PODCAST_BOX_FILE, function () {
        include_once PODCAST_BOX_INCLUDES . '/class-install.php';
        Podcast_Box_Install::deactivate();
    } );
    //Include the base plugin file.
    include_once PODCAST_BOX_INCLUDES . '/base.php';
}
