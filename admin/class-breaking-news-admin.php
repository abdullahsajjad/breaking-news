<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      1.0.0
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/admin
 * @author     Abdullah Sajjad <abdullahsajjad33@gmail.com>
 */
class Breaking_News_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Breaking_News_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Breaking_News_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/css/breaking-news-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'assets/js/breaking-news-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds the Settings Page in Admin Dashboard
	 *
	 * @since 0.0.1
	 */
	public function add_admin_page() {
		add_menu_page( __( 'Breaking News', 'breaking-news' ),
			'Breaking News',
			'manage_options',
			'breaking-news',
			[ $this, 'settings_page_callback' ],
			'dashicons-welcome-widgets-menus',
			90
		);
	}

	/**
	 * Settings Page Callback method
	 *
	 * @since 0.0.1
	 */
	public function settings_page_callback() {
		require_once plugin_dir_path( __FILE__ ) . 'templates/breaking-news-admin-display.php';
	}

	/**
	 * Adds Custom Meta boxes of the plugin
     *
     * @since 0.0.1
	 */
	public function add_custom_meta_box() {
		add_meta_box( 'bn_fields', __( 'Breaking News' ),
			[ $this, 'bn_meta_box_callback' ],
			$this->get_supported_post_types(),
			'advanced',
			'high'
		);
	}

	/**
	 * Return names of all registered post types
	 *
	 * @access protected
	 * @return mixed|void
	 * @since 0.0.1
	 */
	protected function get_supported_post_types() {
		$args = [
			'public' => true,
		];

		$output   = 'names'; // 'names' or 'objects' (default: 'names')
		$operator = 'and'; // 'and' or 'or' (default: 'and')

		return apply_filters( 'bn_supported_post_types', get_post_types( $args, $output, $operator ) );
	}

	/**
	 * Custom metabox Callback, displays all custom meta fields
	 *
	 * Meta Fields
	 * - Enable Breaking News (checkbox) => is_breaking_news
	 * - Custom title (text)             => bn_custom_title
	 * - Set Expiry Date (checkbox)      => bn_expiry
	 * - Expiry Date (Date)              => bn_expiry_date
	 * - Expiry Time (Time)              => bn_expiry_time
	 *
	 * @since 0.0.1
	 */
	public function bn_meta_box_callback( $post ) {

		wp_nonce_field( 'bn_meta_nonce', 'bn_meta_nonce' );

		$bn_settings = get_option( 'bn_settings' );

		$current_breaking_news = '';
		if( isset( $bn_settings['post_id'] ) && !empty( $bn_settings['post_id'] ) ) {
            $current_breaking_news = $bn_settings['post_id'];
        }

		$bn_is_expiry    = get_post_meta( $post->ID, 'bn-is-expiry', true );
		$bn_custom_title = get_post_meta( $post->ID, 'bn-custom-title', true );
		$bn_expiry       = $this->bn_date_time( $post->ID );
		?>
        <div class="bn-meta-main">
            <div class="bn-meta-control">
                <label class="bn-label" for="is-breaking-news">Make this Post Breking News</label>
                <label class="bn-checkbox">
                    <input name="is_breaking_news" id="is-breaking-news" type="checkbox" <?php checked( $current_breaking_news, $post->ID ); ?> />
                    <span class="slider round"></span>
                </label>
                <small class="bn-small-description">Enable to make this post a breaking news</small>
            </div>
            <div class="bn-meta-control">
                <label class="bn-label" for="bn-custom-title">Custom Title</label>
                <input class="bn-meta-field" name="bn_custom_title" id="bn-custom-title" type="text" value="<?php echo $bn_custom_title ?? '';?>"/>
                <small class="bn-small-description">Add a custom title to display instead of post title</small>
            </div>
            <div class="bn-meta-control">
                <label class="bn-label" for="bn-expiry">Set Expiry Date</label>
                <label class="bn-checkbox">
                    <input name="bn_expiry" id="bn-expiry" type="checkbox" <?php checked( $bn_is_expiry, 'on' ) ?>/>
                    <span class="slider round"></span>
                </label>
                <small class="bn-small-description">Enable to add an expiry date</small>
            </div>
            <div class="bn-meta-control <?php echo $bn_is_expiry !== 'on' ? 'bn-hide' : '';?>">
                <label class="bn-label" for="bn-expiry-date">Expiry Date & Time</label>
                <input class="bn-meta-field" name="bn_expiry_date" id="bn-expiry-date" type="date" value="<?php echo $bn_expiry['expiry_date'] ?? '' ;?>"/>
                <input class="bn-meta-field" name="bn_expiry_time" id="bn-expiry-time" type="time" value="<?php echo $bn_expiry['expiry_time'] ?? '' ;?>"/>
                <small class="bn-small-description">Set Expiry Date & Time</small>
            </div>
		</div> <!-- bn-meta-main -->
		<?php

	}

	/**
	 * Save Meta Fields data on post save
	 *
	 * Meta Keys
	 * - bn-custom-title
	 * - bn-is-expiry
	 * - bn-expiry-date-time
	 *
	 * @param $post_id
	 *
	 * @since 0.0.1
	 */
	public function save_meta_data( $post_id ) {

		// Return if we're doing an auto save
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		} // endif

		// nonce Verification
		if ( ! wp_verify_nonce( filter_input( INPUT_POST, 'bn_meta_nonce' ), 'bn_meta_nonce' ) ) {
			return;
		} // endif


		$is_breaking_news = filter_input( INPUT_POST, 'is_breaking_news', FILTER_SANITIZE_STRING );

		if ( $is_breaking_news !== 'on' ) {
			return;
		} // endif

		$bn_custom_title     = filter_input( INPUT_POST, 'bn_custom_title', FILTER_SANITIZE_STRING );
		$bn_expiry           = filter_input( INPUT_POST, 'bn_expiry', FILTER_SANITIZE_STRING );
		$bn_expiry_date_time = filter_input( INPUT_POST, 'bn_expiry_date', FILTER_SANITIZE_STRING ) .
		                       ' ' .
		                       filter_input( INPUT_POST, 'bn_expiry_time', FILTER_SANITIZE_STRING );

		//get breaking news post from options
		$bn_settings = get_option( 'bn_settings' );

		$bn_settings['post_id'] = $post_id;

		update_option( 'bn_settings', $bn_settings );

		update_post_meta( $post_id, 'bn-custom-title', $bn_custom_title );
		update_post_meta( $post_id, 'bn-is-expiry', $bn_expiry );
		update_post_meta( $post_id, 'bn-expiry-date-time', strtotime( $bn_expiry_date_time ) );

	}

	/**
	 * Returns Breaking News Expiry Date and time
	 *
	 * Gets UNIX timestamp from post meta and returns
	 * the expiry date-time in an associative array
	 *
	 * @param $post_ID
	 *
	 * @return array
	 * @since 0.0.1
	 */
	function bn_date_time( $post_ID ): array {
		$expiry_date_time = get_post_meta( $post_ID, 'bn-expiry-date-time', true );
		$expiry           = [];
		if ( $expiry_date_time !== '' ) {
			$expiry = explode( ' ', date( 'Y-m-d H:i', $expiry_date_time ) );
			$expiry = array_combine( [ 'expiry_date', 'expiry_time', ], $expiry );
		}

		if ( ! empty( $expiry ) ) {
			return $expiry;
		}

		return [];
	}

}
