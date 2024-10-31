<?php

defined( 'ABSPATH' ) || exit;
if ( !class_exists( 'Podcast_Box_Form_Handler' ) ) {
    class Podcast_Box_Form_Handler
    {
        /** @var null */
        private static  $instance = null ;
        /**
         * Podcast_Box_Form_Handler constructor.
         */
        public function __construct()
        {
            add_action( 'wp_ajax_podcast_search', [ $this, 'podcast_search' ] );
            add_action( 'wp_ajax_nopriv_podcast_search', [ $this, 'podcast_search' ] );
            add_action( 'wp_ajax_episode_search', [ $this, 'episode_search' ] );
            add_action( 'wp_ajax_nopriv_episode_search', [ $this, 'episode_search' ] );
            add_action( 'wp_ajax_podcast_box_pagination', [ $this, 'pagination' ] );
            add_action( 'wp_ajax_nopriv_podcast_box_pagination', [ $this, 'pagination' ] );
            // Delete episodes
            add_action( 'wp_ajax_delete_episodes', [ $this, 'delete_episodes' ] );
            add_action( 'wp_ajax_podcast_box_player_next_prev', array( $this, 'next_prev' ) );
            add_action( 'wp_ajax_nopriv_podcast_box_player_next_prev', array( $this, 'next_prev' ) );
        }
        
        public function next_prev()
        {
            $current_id = ( !empty($_REQUEST['currentId']) ? intval( $_REQUEST['currentId'] ) : 0 );
            $prev_next = ( !empty($_REQUEST['prevNext']) ? sanitize_key( $_REQUEST['prevNext'] ) : '' );
            $data = podcast_box_get_next_prev_data( $current_id, $prev_next );
            ( $data ? wp_send_json_success( $data ) : wp_send_json_error( __( 'No Post.', 'podcast-box' ) ) );
            exit;
        }
        
        public function delete_episodes()
        {
            if ( !current_user_can( 'delete_posts' ) ) {
                return;
            }
            $podcast_id = intval( $_REQUEST['podcast_id'] );
            global  $wpdb ;
            $episode_ids = podcast_box_get_episode_ids( $podcast_id );
            
            if ( !empty($episode_ids) ) {
                $placeholders = '';
                foreach ( $episode_ids as $episode_id ) {
                    $placeholders .= '%s,';
                }
                $prepared_placeholders = trim( $placeholders, ',' );
                $prepared_values = array_merge( array( 'episode' ), $episode_ids );
                // Delete posts + data.
                $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->posts} WHERE post_type = %s AND ID IN ({$prepared_placeholders});", $prepared_values ) );
                $wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
                //Delete relation
                $wpdb->query( "DELETE releation FROM {$wpdb->prefix}podcast_episode_relation releation LEFT JOIN {$wpdb->posts} posts ON posts.ID = releation.episode_id WHERE posts.ID IS NULL;" );
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
            die;
        }
        
        public function pagination()
        {
            $page = ( !empty($_REQUEST['page']) ? intval( $_REQUEST['page'] ) : '' );
            $type = ( !empty($_REQUEST['type']) ? sanitize_text_field( $_REQUEST['type'] ) : '' );
            ob_start();
            
            if ( 'podcast' == $type ) {
                $args = ( !empty($_REQUEST['args']) ? wp_unslash( $_REQUEST['args'] ) : [] );
                $args['paged'] = $page;
                $query = podcast_box_get_podcasts( $args, true );
                $total_pages = $query->max_num_pages;
                if ( !empty($query->posts) ) {
                    foreach ( $query->posts as $podcast ) {
                        podcast_box()->get_template( 'loop-podcast', [
                            'podcast' => $podcast,
                        ] );
                    }
                }
            } else {
                $length = podcast_box_get_settings( 'episodes_per_page', '10', 'podcast_box_display_settings' );
                $offset = ($page - 1) * $length;
                $podcast_id = ( !empty($_REQUEST['podcast_id']) ? intval( $_REQUEST['podcast_id'] ) : '' );
                $sort = ( !empty($_REQUEST['sort']) ? sanitize_key( $_REQUEST['sort'] ) : 'asc' );
                $episode_ids = podcast_box_get_episode_ids( $podcast_id, $sort );
                $total_pages = ceil( count( $episode_ids ) / $length );
                $episode_ids = array_slice( $episode_ids, $offset, $length );
                if ( !empty($episode_ids) ) {
                    foreach ( $episode_ids as $episode_id ) {
                        podcast_box()->get_template( 'loop-episode', [
                            'episode_id' => $episode_id,
                        ] );
                    }
                }
            }
            
            $return['html'] = ob_get_clean();
            //pagination
            ob_start();
            
            if ( 'podcast' == $type ) {
                podcast_box_pagination( $page, $total_pages );
            } else {
                podcast_box_pagination(
                    $page,
                    $total_pages,
                    'episode',
                    $podcast_id
                );
            }
            
            $return['pagination'] = ob_get_clean();
            //listing top
            
            if ( 'podcast' == $type ) {
                ob_start();
                podcast_box()->get_template( 'listing-top', [
                    'query' => $query,
                ] );
                $return['listing_top'] = ob_get_clean();
            }
            
            wp_send_json_success( $return );
        }
        
        public function podcast_search()
        {
            $term = ( !empty($_REQUEST['term']['term']) ? sanitize_text_field( $_REQUEST['term']['term'] ) : '' );
            $data[] = [
                'id'   => 0,
                'text' => __( 'No podcast found', 'podcast-box' ),
            ];
            $posts = podcast_box_get_podcasts( [
                's'              => $term,
                'posts_per_page' => 25,
            ] );
            if ( !empty($term) ) {
                
                if ( !empty($posts) ) {
                    $data = [];
                    foreach ( $posts as $post ) {
                        $item = [];
                        $item['id'] = $post->ID;
                        $item['link'] = get_the_permalink( $post->ID );
                        $item['text'] = $post->post_title;
                        $item['image'] = podcast_box_get_meta( $post->ID, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.svg' );
                        
                        if ( podcast_box_get_country( $post->ID ) ) {
                            $country = podcast_box_get_country( $post->ID );
                            $item['country'] = $country->name;
                        }
                        
                        $data[] = $item;
                    }
                }
            
            }
            echo  json_encode( $data ) ;
            die;
        }
        
        public function episode_search()
        {
            $term = ( !empty($_REQUEST['term']['term']) ? sanitize_text_field( $_REQUEST['term']['term'] ) : '' );
            $podcast_id = ( !empty($_REQUEST['podcast_id']) ? sanitize_text_field( $_REQUEST['podcast_id'] ) : '' );
            $data[] = [
                'id'   => 0,
                'text' => __( 'No episode found.', 'podcast-box' ),
            ];
            $args = [
                's'              => $term,
                'posts_per_page' => 25,
                'meta_key'       => 'podcast',
                'meta_value'     => $podcast_id,
            ];
            $posts = podcast_box_get_episodes( $args );
            if ( !empty($term) ) {
                
                if ( !empty($posts) ) {
                    $data = [];
                    foreach ( $posts as $post ) {
                        $item = [];
                        $item['id'] = $post->ID;
                        $item['link'] = get_the_permalink( $post->ID );
                        $item['text'] = $post->post_title;
                        $item['image'] = podcast_box_get_meta( $post->ID, 'logo', PODCAST_BOX_ASSETS . '/images/placeholder.svg' );
                        $data[] = $item;
                    }
                }
            
            }
            echo  json_encode( $data ) ;
            die;
        }
        
        /**
         * Remove country and it's stations from import page,
         *
         * @return void
         * @since 2.0.7
         *
         */
        public function remove_country()
        {
            if ( !wp_verify_nonce( $_REQUEST['nonce'], 'podcast-box' ) ) {
                return;
            }
            $country = sanitize_key( $_REQUEST['country'] );
            global  $wpdb ;
            $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT tr.object_id FROM {$wpdb->terms} t LEFT JOIN {$wpdb->term_relationships} tr ON tr.term_taxonomy_id = t.term_id WHERE t.slug = %s;", $country ) );
            
            if ( !empty($post_ids) ) {
                $placeholders = '';
                foreach ( $post_ids as $post_id ) {
                    $placeholders .= '%s,';
                }
                $prepared_placeholders = trim( $placeholders, ',' );
                // Delete podcasts + meta.
                $wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->posts} WHERE post_type = 'podcast' AND ID IN ({$prepared_placeholders});", $post_ids ) );
                //delete podcast episodes
                $wpdb->query( $wpdb->prepare( "DELETE p FROM {$wpdb->posts} p LEFT JOIN {$wpdb->postmeta} m ON p.ID = m.post_id WHERE p.post_type = 'episode' AND m.meta_key = 'podcast' AND m.meta_value IN ({$prepared_placeholders});", $post_ids ) );
                //delete relation
                $wpdb->query( "DELETE releation FROM {$wpdb->prefix}podcast_episode_relation releation LEFT JOIN {$wpdb->posts} posts ON posts.ID = releation.episode_id WHERE posts.ID IS NULL;" );
                //delete meta
                $wpdb->query( "DELETE meta FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE posts.ID IS NULL;" );
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
            $countries = (array) get_option( 'podcast_box_imported_countries' );
            if ( ($key = array_search( $country, $countries )) !== false ) {
                unset( $countries[$key] );
            }
            update_option( 'podcast_box_imported_countries', (array) $countries );
            die;
        }
        
        public function handle_podcasts_import()
        {
            $countries = ( !empty($_REQUEST['countries']) ? array_map( 'sanitize_key', $_REQUEST['countries'] ) : '' );
            include_once PODCAST_BOX_INCLUDES . '/admin/class-importer__premium_only.php';
            $importer = Podcast_Box_Importer::instance( $countries );
            $response = $importer->handle_import();
            wp_send_json_success( $response );
        }
        
        /**
         * @return Podcast_Box_Form_Handler|null
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
Podcast_Box_Form_Handler::instance();