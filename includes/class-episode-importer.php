<?php

defined( 'ABSPATH' ) || exit();


if ( ! class_exists( 'Podcast_Box_Episode_Importer' ) ) {
	class Podcast_Box_Episode_Importer {
		/** @var null */
		private static $instance = null;

		/**
		 * Podcast_Box_Episode_Importer constructor.
		 */
		public function __construct() {
			add_action( 'podcast_box/automatic_import_hook', [ $this, 'cron_import' ] );
			add_action( 'admin_action_podcast_box_import_now', [ $this, 'manual_import' ] );
		}

		/**
		 * Import Episode Manually
		 */
		public function manual_import() {
			if ( empty( $_GET['post_id'] ) ) {
				return;
			}

			$post_id = intval( $_GET['post_id'] );
			$this->import( $post_id, true );

		}

		public function cron_import() {
			global $wpdb;

			$sql = $wpdb->prepare( "SELECT ID FROM {$wpdb->posts}  WHERE post_status = %s AND post_type = %s ORDER BY RAND()", [ 'publish', 'podcast', ] );

			$podcast_ids = $wpdb->get_col( $sql );

			if ( empty( $podcast_ids ) ) {
				return;
			}

			foreach ( $podcast_ids as $podcast_id ) {
				$last_run     = podcast_box_get_meta( $podcast_id, 'last_run', 0 );

				$current_time = current_time( 'timestamp' );
				$diff         = $current_time - strtotime( $last_run );

				$import_interval = podcast_box_get_meta( $podcast_id, 'import_interval', 'daily' );

				$frequency = 'daily' == $import_interval ? DAY_IN_SECONDS : 7 * DAY_IN_SECONDS;

				if ( $diff < $frequency ) {
					continue;
				}

				$this->import( $podcast_id );
			}
		}

		/**
		 * Import episodes
		 *
		 * @param         $podcast_id
		 * @param   bool  $is_manual
		 */
		public function import( $podcast_id, $is_manual = false ) {

			$posts_added_count = 0;

			// Increase the time limit
			set_time_limit( 360 );

			// Load post.php class for post manipulations during cron
			if ( ( ! is_admin() ) || ( ! function_exists( 'post_exists' ) ) ) {
				require_once( ABSPATH . 'wp-admin/includes/post.php' );
			}

			// Parse the RSS/XML feed
			$content_tag = 'content:encoded';    // default

			$rss_url = podcast_box_get_meta( $podcast_id, 'feed_url' );

			//check URL
			if ( empty( $rss_url ) ) {
				if ( $is_manual ) {
					podcast_box()->add_notice( 'error', 'No valid RSS URL found' );
					podcast_box_redirect_to_edit( $podcast_id );
				} else {
					return;
				}
			}

			$rss_feed_url = esc_url( $rss_url, array( 'http', 'https' ) );

			$rss_feed = @simplexml_load_file( $rss_feed_url );

			if ( empty( $rss_feed ) && ! empty( $rss_feed_url ) ) {

				$ch = curl_init( $rss_feed_url );

				curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0' );

				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
				$result = curl_exec( $ch );
				if ( substr( $result, 0, 5 ) == "<?xml" ) {
					$rss_feed = simplexml_load_string( $result );
				} else {
					if ( $is_manual ) {
						podcast_box()->add_notice( 'error', 'Feed URL is not valid.' );
						podcast_box_redirect_to_edit( $podcast_id );
					} else {
						return;
					}
				}

				curl_close( $ch );

			}

			// Set up a new post per item that appears in the feed
			if ( ! empty( $rss_feed ) ) {

				$episode_count = count( $rss_feed->channel->item );

				for ( $i = 0; $i < $episode_count; $i ++ ) {

					$item   = $rss_feed->channel->item[ $i ];
					$itunes = $item->children( 'http://www.itunes.com/dtds/podcast-1.0.dtd' );
					$guid   = podcast_box_sanitize_data( $item->guid );

					// Post date -  Ensure posts are published right away (for server/feed timezone conflicts)
					if ( strtotime( (string) $item->pubDate ) < current_time( 'timestamp' ) ) {
						$timestamp_post_date = strtotime( (string) $item->pubDate );
					} else {
						$timestamp_post_date = current_time( 'timestamp' );
					}

					$post_date = date( 'Y-m-d H:i:s', $timestamp_post_date );

					//post title
					$post_title = podcast_box_sanitize_data( $item->title );

					// Grab the content
					if ( ! empty( $item->children( 'itunes', true )->summary ) && $content_tag === 'itunes:summary' ) {
						$parsed_content = podcast_box_sanitize_data( $item->children( 'itunes', true )->summary );
					} elseif ( ! empty( $item->children( 'itunes', true )->encoded ) && $content_tag === 'content:encoded' ) {
						$parsed_content = podcast_box_sanitize_data( $item->children( 'content', true )->encoded );
					} elseif ( ! empty( $item->description ) && $content_tag === 'description' ) {
						$parsed_content = podcast_box_sanitize_data( $item->description );
						// If no preference cuts the mustard, try all the defaults
					} elseif ( ! empty( $item->children( 'content', true )->encoded ) ) {
						$parsed_content = podcast_box_sanitize_data( $item->children( 'content', true )->encoded );
					} elseif ( ! empty( $item->description ) ) {
						$parsed_content = podcast_box_sanitize_data( $item->description );
					} else {
						$parsed_content = podcast_box_sanitize_data( $itunes->summary );
					}

					// Create post data
					$post = array(
						'post_author'  => '',
						'post_content' => $parsed_content,
						'post_date'    => $post_date,
						'post_excerpt' => podcast_box_sanitize_data( $itunes->subtitle ),
						'post_status'  => 'publish',
						'post_type'    => 'episode',
						'post_title'   => $post_title,
					);


					// Create the post
					global $wpdb;

					// Check if post already exists, if so - skip. First we'll look for the GUID, then at the title.
					if ( ! empty( $guid ) && $guid != '' ) {
						$query      = "SELECT COUNT(*) FROM {$wpdb->postmeta} WHERE (meta_key = 'imported_guid' AND meta_value LIKE '%$guid%')";
						$guid_count = intval( $wpdb->get_var( $query ) );
					} else {
						$guid_count = 0;
					}

					// If post not exists
					if ( $guid_count == 0 ) {

						if ( 0 === post_exists( $post_title, "", "", 'episode' ) ) {

							$post_id = wp_insert_post( $post );

							// Continue if the import generate errors
							if ( is_wp_error( $post_id ) ) {
								continue;
							}

							// Add GUID for each post
							update_post_meta( $post_id, 'imported_guid', $guid );

							//episode number
							$episode_number = podcast_box_sanitize_data( $itunes->episode );
							update_post_meta( $post_id, 'episode_number', $episode_number );

							//season number
							$season_number = podcast_box_sanitize_data( $itunes->season );
							update_post_meta( $post_id, 'season_number', $season_number );

							// podcast
							update_post_meta( $post_id, 'podcast', $podcast_id );

							// duration
							$duration = ! empty( $itunes->duration ) ? podcast_box_sanitize_data( $itunes->duration ) : '';
							update_post_meta( $post_id, 'duration', $duration );

							// logo
							$img_url = ! empty( $itunes ) && $itunes->image && $itunes->image->attributes()
								? (string) $itunes->image->attributes()->href : '';
							update_post_meta( $post_id, 'logo', $img_url );

							// audio file
							$audio_url = (string) $item->enclosure['url'];
							$audio_url = preg_replace( '/(?s:.*)(https?:\/\/(?:[\w\-\.]+[^#?\s]+)(?:\.mp3))(?s:.*)/', '$1', $audio_url );
							$audio_url = preg_replace( '/(?s:.*)(https?:\/\/(?:[\w\-\.]+[^#?\s]+)(?:\.m4a))(?s:.*)/', '$1', $audio_url );
							update_post_meta( $post_id, 'file', $audio_url );

							// episode link
							$feed_link_url = (string) $item->link;
							update_post_meta( $post_id, 'link', $feed_link_url );

							// file size
							$size = ! empty( $item->enclosure['length'] ) ? $item->enclosure['length'] : 0;
							$size = '' . number_format( $size / 1048576, 2 ) . 'M';
							update_post_meta( $post_id, 'size', $size );

							// update last run time
							update_post_meta( $podcast_id, 'last_run', current_time( 'mysql' ) );

							// podcast - episode relation
							podcast_box_insert_podcast_episode_relation( $podcast_id, $post_id );

							$posts_added_count ++; // Count successfully imported episodes

						}
					}
				}

				// update last run time
				update_post_meta( $podcast_id, 'last_run', current_time( 'mysql' ) );

				// show import message
				if ( $is_manual ) {

					$type = 'error';
					if ( $posts_added_count == 0 && $episode_count != 0 ) { // No episodes imported due to duplicated titles.
						$notice = esc_html__( 'No new episodes imported, all episodes already existing in WordPress!',
							'podcast-box' );
						$notice .= '<br><br><span class="slt-existing-post-notice">'
						           . esc_html__( 'If you have existing draft, private or trashed posts with the same title as your episodes, delete those and run the importer again',
								'podcast-box' ) . '</span>';
					} elseif ( $episode_count == 0 ) { // No episodes existing within feed.
						$notice = esc_html__( 'Error! Your feed does not contain any episodes.', 'podcast-box' );
					} else {
						$type   = 'success';
						$notice = '<strong>' . esc_html__( 'Success! Imported ', 'podcast-box' ) . $posts_added_count
						          . esc_html__( ' out of ', 'podcast-box' ) . $episode_count . esc_html__( ' episodes',
								'podcast-box' ) . '</strong>';
					}

					podcast_box()->add_notice( $type, $notice );
					podcast_box_redirect_to_edit( $podcast_id );
				}

			} else {

				// show import message
				if ( $is_manual ) {
					$notice = '<strong>' . esc_html__( 'Podcast Feed Error! Please use a valid RSS feed URL.',
							'podcast-box' ) . '</strong>';

					podcast_box()->add_notice( 'error', $notice );
					podcast_box_redirect_to_edit( $podcast_id );
				}
			}

		}

		/**
		 * @return Podcast_Box_Episode_Importer|null
		 */
		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}
	}

}

Podcast_Box_Episode_Importer::instance();