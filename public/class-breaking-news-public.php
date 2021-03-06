<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/public
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class Breaking_News_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Display Breaking News After SIte Header
     *
     * hooked into wp_footer and breaking news div is added
     * after site header using jquery
     *
     * @since 0.0.1
	 */
	public function display_breaking_news() {
		$bn_settings = get_option( 'bn_settings' );

		if ( ! isset( $bn_settings['post_id'] ) ) {
			return;
		}

		// checking expiry
		if( $this->check_breaking_news_expiry( $bn_settings['post_id'] ) === TRUE ) {
            return;
		}

		$title = get_post_meta( $bn_settings['post_id'], 'bn-custom-title', true );
		if ( $title === '' || $title === false ) {
			$title = get_the_title( $bn_settings['post_id'] );
		}

		$bg_color    = $bn_settings['background_color'] ?? '#ff0000';
		$txt_color   = $bn_settings['text_color'] ?? '#ffffff';
		$bn_position = $bn_settings['bn_position'] ?? 'after-header';
		$html        = "{$bn_settings['news_title']}&nbsp;<a style='color:$txt_color;' href=" . get_the_permalink( $bn_settings['post_id'] ) . ">$title</a>";

 		?>
        <script type="text/javascript">

            /**
             * Insert new node after reference node
             *
             * @param {HTMLElement} referenceNode
             * @param {HTMLDivElement|T} newNode
             *
             * @since 0.1.0
             */
            function insertAfter(referenceNode, newNode) {
                referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
            }

            /**
             * Insert new node after reference node
             *
             * @param {HTMLElement} referenceNode
             * @param {HTMLDivElement|T} newNode
             *
             * @since 0.1.0
             */
            function insertBefore(referenceNode, newNode) {
                referenceNode.parentNode.insertBefore(newNode, referenceNode.previousSibling);
            }

            document.addEventListener('DOMContentLoaded', function() { // fires on DOM Load

                const el = document.createElement("div");
                el.classList.add('breaking-news');
                el.innerHTML             = "<?php echo $html ?>";
                el.style.backgroundColor = "<?php echo $bg_color; ?>";
                el.style.color           = "<?php echo $txt_color; ?>";
                el.style.padding         = "10px";
                el.style.textTransform   = "Capitalize";
                el.style.textAlign       = "center";
                el.style.position        = "relative"; // added after testing on twenty seventeen

                let header = document.querySelector(".site-header");
                if( header === undefined || header === null ) {
                    header = document.querySelector("#site-header");
                }
                <?php
                if( $bn_position === 'after-header' ) {
                    ?>
                    insertAfter(header, el);
                    <?php
                } elseif( $bn_position === 'before-header' ) {
                    ?>
                    insertBefore(header, el);
                    <?php
                }
                ?>

            })

        </script>
		<?php
	}

	/**
	 * Checks The Breaking News Expiry and unset
	 * the breaking news from options
	 *
	 * @param $post_ID
	 *
	 * @return boolean
	 *
	 * @since 0.0.1
	 */
	protected function check_breaking_news_expiry( $post_ID ): bool {
		$is_expiry = get_post_meta( $post_ID, 'bn-is-expiry', true );
		if( empty( $is_expiry ) ) {
		    return FALSE;
        }

		$expiry_date_time = get_post_meta( $post_ID, 'bn-expiry-date-time', true );
		$current_time     = strtotime( current_time( 'mysql' ) );

		if ( $current_time < $expiry_date_time ) {   // expire time is less than current time
			return false;
		}

		// unset breaking news
		$bn_settings = get_option( 'bn_settings' );
		unset( $bn_settings['post_id'] );
		update_option( 'bn_settings', $bn_settings );

		return true;
	}
}
