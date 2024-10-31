<?php

defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'Podcast_Box_Hooks' ) ) {
    class Podcast_Box_Hooks
    {
        /** @var null */
        private static  $instance = null ;
        /**
         * Podcast_Box_Hooks constructor.
         */
        public function __construct()
        {
            //single podcast page
            add_filter( 'the_content', [ $this, 'single_podcast_page' ] );
            //single episode page
            add_filter( 'the_content', [ $this, 'single_episode_page' ] );
            //render the podcast player
            add_action( 'wp_footer', [ $this, 'render_player' ] );
            // Delete podcast-episode relation
            add_action( 'delete_post', [ $this, 'delete_relation' ] );
            //filter the podcast archive title
            add_filter(
                'get_the_archive_title',
                [ $this, 'archive_title' ],
                10,
                2
            );
            //popup player
            add_action( 'template_redirect', [ $this, 'popup_player' ] );
            //filter previous-next post
            add_filter( 'get_previous_post_join', [ $this, 'prev_next_post_join' ] );
            add_filter( 'get_next_post_join', [ $this, 'prev_next_post_join' ] );
            add_filter( 'get_next_post_where', [ $this, 'next_post_where' ] );
            add_filter( 'get_previous_post_where', [ $this, 'previous_post_where' ] );
            add_action( 'wpmilitary_settings/after_content', [ $this, 'settings_promo' ] );
            pb_fs()->add_action( 'after_uninstall', [ $this, 'uninstall' ] );
        }
        
        public function settings_promo()
        {
            include_once PODCAST_BOX_INCLUDES . '/admin/views/promo.php';
        }
        
        /**
         *  Remove all data created by podcast-box
         *
         * Uninstalling WP Radio deletes stations, settings
         *
         * @since 2.0.2
         */
        public function uninstall()
        {
            $delete_data = 'on' == podcast_box_get_settings( 'delete_data', 'off' );
            
            if ( $delete_data ) {
                global  $wpdb ;
                //delete pages
                $listing_page = podcast_box_get_settings( 'listing_page' );
                if ( $listing_page ) {
                    wp_delete_post( $listing_page, true );
                }
                // Delete options.
                $wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'podcast\\_box\\_%';" );
                // Delete posts + data.
                $wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ('podcast', 'episode');" );
                $wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
                // Delete term taxonomies.
                foreach ( array( 'podcast_country', 'podcast_category' ) as $taxonomy ) {
                    $wpdb->delete( $wpdb->term_taxonomy, array(
                        'taxonomy' => $taxonomy,
                    ) );
                }
                // Delete orphan relationships.
                $wpdb->query( "DELETE tr FROM {$wpdb->term_relationships} tr LEFT JOIN {$wpdb->posts} posts ON posts.ID = tr.object_id WHERE posts.ID IS NULL;" );
                // Delete orphan terms.
                $wpdb->query( "DELETE t FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
                // Delete orphan term meta.
                if ( !empty($wpdb->termmeta) ) {
                    $wpdb->query( "DELETE tm FROM {$wpdb->termmeta} tm LEFT JOIN {$wpdb->term_taxonomy} tt ON tm.term_id = tt.term_id WHERE tt.term_id IS NULL;" );
                }
                // Clear any cached data that has been removed.
                wp_cache_flush();
            }
        
        }
        
        /**
         * Filter Previous/ Next Station Link
         *
         * @param $join
         *
         * @return string
         */
        public function prev_next_post_join( $join )
        {
            global  $post, $wpdb ;
            if ( get_post_type( $post ) == 'podcast' ) {
                $join = " INNER JOIN {$wpdb->term_relationships} AS tr ON p.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";
            }
            if ( get_post_type( $post ) == 'episode' ) {
                $join = " INNER JOIN {$wpdb->postmeta} AS m ON p.ID = m.post_id ";
            }
            return $join;
        }
        
        public function next_post_where( $where )
        {
            global  $post, $wpdb ;
            
            if ( get_post_type( $post ) == 'podcast' ) {
                $where = $wpdb->prepare( "WHERE p.post_date > %s AND p.post_type = 'podcast' AND p.post_status = 'publish'", $post->post_date );
                $term_array = wp_get_object_terms( $post->ID, 'podcast_country', array(
                    'fields' => 'ids',
                ) );
                $term_array = array_map( 'intval', $term_array );
                if ( !$term_array || is_wp_error( $term_array ) ) {
                    return '';
                }
                $where .= ' AND tt.term_id IN (' . implode( ',', $term_array ) . ')';
            }
            
            
            if ( get_post_type( $post ) == 'episode' ) {
                $where = $wpdb->prepare( "WHERE p.post_date > %s AND p.post_type = 'episode' AND p.post_status = 'publish'", $post->post_date );
                $podcast_id = podcast_box_get_episode_podcast( $post->ID );
                $where .= $wpdb->prepare( " AND m.meta_key = 'podcast'  AND m.meta_value = %d ", $podcast_id );
            }
            
            return $where;
        }
        
        public function previous_post_where( $where )
        {
            global  $post, $wpdb ;
            
            if ( get_post_type( $post ) == 'podcast' ) {
                $where = $wpdb->prepare( "WHERE p.post_date < %s AND p.post_type = 'podcast' AND p.post_status = 'publish'", $post->post_date );
                $term_array = wp_get_object_terms( $post->ID, 'podcast_country', array(
                    'fields' => 'ids',
                ) );
                $term_array = array_map( 'intval', $term_array );
                if ( !$term_array || is_wp_error( $term_array ) ) {
                    return '';
                }
                $where .= ' AND tt.term_id IN (' . implode( ',', $term_array ) . ')';
            }
            
            
            if ( get_post_type( $post ) == 'episode' ) {
                $where = $wpdb->prepare( "WHERE p.post_date < %s AND p.post_type = 'episode' AND p.post_status = 'publish'", $post->post_date );
                $podcast_id = podcast_box_get_episode_podcast( $post->ID );
                $where .= $wpdb->prepare( " AND m.meta_key = 'podcast'  AND m.meta_value = %d ", $podcast_id );
            }
            
            return $where;
        }
        
        public function delete_relation( $post_id )
        {
            $post_type = get_post_type( $post_id );
            if ( 'episode' == $post_type ) {
                podcast_box_delete_episode_relation( $post_id );
            }
            if ( 'podcast' == $post_type ) {
                podcast_box_delete_podcast_relation( $post_id );
            }
        }
        
        public function render_player()
        {
            podcast_box()->get_template( 'player' );
        }
        
        public function single_podcast_page( $content )
        {
            
            if ( is_singular( 'podcast' ) ) {
                ob_start();
                podcast_box()->get_template( 'single-podcast' );
                $content = ob_get_clean();
            }
            
            return $content;
        }
        
        public function single_episode_page( $content )
        {
            
            if ( is_singular( 'episode' ) ) {
                ob_start();
                podcast_box()->get_template( 'single-episode' );
                $content = ob_get_clean();
            }
            
            return $content;
        }
        
        public function archive_title( $title, $original_title )
        {
            if ( is_tax( 'podcast_country' ) || is_tax( 'podcast_category' ) ) {
                $title = $original_title . __( ' - Podcasts', 'podcast-box' );
            }
            return $title;
        }
        
        public function popup_player()
        {
            
            if ( !empty($_GET['podcast_player']) ) {
                $episode_id = intval( $_GET['podcast_player'] );
                ?>

				<!doctype html>
				<html lang="en">
				<head>
					<meta charset="UTF-8">
					<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
					<meta http-equiv="X-UA-Compatible" content="ie=edge">
					<link rel="stylesheet" href="<?php 
                echo  site_url( '/wp-includes/css/dashicons.css' ) ;
                ?>">
					<link rel="stylesheet" href="<?php 
                echo  PODCAST_BOX_ASSETS . '/vendor/select2/select2.min.css' ;
                ?>">
					<link rel="stylesheet" href="<?php 
                echo  PODCAST_BOX_ASSETS . '/css/frontend.css' ;
                ?>">

					<script src="<?php 
                echo  site_url( '/wp-includes/js/jquery/jquery.js' ) ;
                ?>"></script>
					<script src="<?php 
                echo  site_url( '/wp-includes/js/jquery/jquery-migrate.min.js' ) ;
                ?>"></script>
					<script src="<?php 
                echo  PODCAST_BOX_ASSETS . '/vendor/hls.js' ;
                ?>"></script>


					<script src="<?php 
                echo  site_url( '/wp-includes/js/jquery/ui/core.min.js' ) ;
                ?>"></script>
					<script src="<?php 
                echo  site_url( '/wp-includes/js/jquery/ui/mouse.min.js' ) ;
                ?>"></script>
					<script src="<?php 
                echo  site_url( '/wp-includes/js/underscore.js' ) ;
                ?>"></script>

                    <script src="<?php 
                echo  site_url( '/wp-includes/js/wp-util.min.js' ) ;
                ?>" id='wp-util-js'></script>
                    <script type='text/javascript' id='wp-util-js-extra'>
                        /* <![CDATA[ */
                        var _wpUtilSettings = <?php 
                echo  json_encode( [
                    'ajax' => [
                    'url' => admin_url( 'admin-ajax.php' ),
                ],
                ] ) ;
                ?>;
                        /* ]]> */
                    </script>

					<script src="<?php 
                echo  site_url( '/wp-includes/js/mediaelement/mediaelement-and-player.min.js' ) ;
                ?>"></script>
					<script src="<?php 
                echo  site_url( '/wp-includes/js/mediaelement/mediaelement-migrate.min.js' ) ;
                ?>"></script>
					<script src="<?php 
                echo  site_url( '/wp-includes/js/mediaelement/wp-mediaelement.min.js' ) ;
                ?>"></script>


                    <title><?php 
                printf( __( 'Playing - %s', 'podcast-box' ), get_the_title( $episode_id ) );
                ?></title>


				</head>
				<body>

				<?php 
                podcast_box()->get_template( 'player', [
                    'player_type' => 'popup',
                    'episode_id'  => $episode_id,
                ] );
                podcast_box()->get_template( 'player', [
                    'player_type' => 'fixed',
                ] );
                ?>

				<script src="<?php 
                echo  site_url( '/wp-includes/js/wp-util.js' ) ;
                ?>"></script>
				<script src="<?php 
                echo  PODCAST_BOX_ASSETS . '/vendor/jquery.lazy.min.js' ;
                ?>"></script>
				<script src="<?php 
                echo  PODCAST_BOX_ASSETS . '/vendor/select2/select2.min.js' ;
                ?>"></script>

				<script>
                    window.podcastBox = <?php 
                echo  wp_json_encode( podcast_box_localize_array() ) ;
                ?>;
				</script>


                <script src="<?php 
                echo  PODCAST_BOX_ASSETS . '/js/frontend.min.js' ;
                ?>"></script>


				</body>
				</html>

				<?php 
                exit;
            }
        
        }
        
        /**
         * @return Podcast_Box_Hooks|null
         */
        public static function instance()
        {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self();
            }
            return self::$instance;
        }
    
    }
}
Podcast_Box_Hooks::instance();