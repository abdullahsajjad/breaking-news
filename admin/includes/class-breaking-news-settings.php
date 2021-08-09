<?php
/**
 * Main Admin Settings Class.
 *
 * This file is used to define admin settings using settings API.
 *
 * @link       https://github.com/abdullahsajjad
 * @since      0.0.1
 *
 * @package    Breaking_News
 * @subpackage Breaking_News/admin/includes
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || die();

/**
 * Main Admin Settings Class
 *
 * this Class is used to define admin settings
 *
 * @since 0.0.1
 */
class Breaking_News_Settings {

	/**
	 * Holds all saved settings options
	 *
	 * @var array $bn_settings
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected $bn_settings;

	/**
	 * Class Constructor
	 */
	public function __construct() {
		$this->bn_settings = get_option( 'bn_settings' );
	}

	/**
	 * Displays settings fields
	 *
	 * @since 0.0.1
	 *
	 * @access public
	 */
	public function do_settings_fields() {
		settings_fields( 'bn_settings' );
		do_settings_sections( 'bn_settings' );
	}

	/**
	 * Registers plugin settings using WP Settings API
	 *
	 * Registered setting - bn_settings
	 * settings_section   - bn_settings_section
	 *
	 * registered settings fields
	 * - bn_title
	 * - bn_background_colour
	 * - bn_text_colour
	 * - bn_post_id
	 *
	 * @since 0.0.1
     *        0.0.3 added settings field to remove plugin data
	 */
	public function bn_register_settings() {
		register_setting( 'bn_settings', 'bn_settings', [
			'sanitize_callback' => [ $this, 'sanitize_settings_fields' ],
		] );

		add_settings_section(
			'bn_settings_section',
			__( 'General Settings', 'breaking-news' ),
			[ $this, 'bn_settings_section_callback' ],
			'bn_settings'
		);  //main section

		add_settings_field(
			'bn_title',
			__( 'Breaking News Title', 'breaking-news' ),
			[ $this, 'bn_title_callback' ],
			'bn_settings',
			'bn_settings_section'
		);  // breaking news title

		add_settings_field(
			'bn_background_colour',
			__( 'Background Color', 'breaking-news' ),
			[ $this, 'bn_bg_color_callback' ],
			'bn_settings',
			'bn_settings_section'
		);  // background color

		add_settings_field(
			'bn_text_colour',
			__( 'Text Color', 'breaking-news' ),
			[ $this, 'bn_text_color_callback' ],
			'bn_settings',
			'bn_settings_section'
		);  // text color

		add_settings_field(
			'bn_post_id',
			__( 'Breaking News Post', 'breaking-news' ),
			[ $this, 'bn_selected_post_callback' ],
			'bn_settings',
			'bn_settings_section'
		);  // breaking news selected post

		add_settings_field(
			'bn_position',
			__( 'Breaking News Position', 'breaking-news' ),
			[ $this, 'bn_selected_position_callback' ],
			'bn_settings',
			'bn_settings_section'
		);  // breaking news position

		add_settings_field(
			'bn_remove_data',
			__( 'Remove Plugin Data', 'breaking-news' ),
			[ $this, 'bn_remove_data_callback' ],
			'bn_settings',
			'bn_settings_section'
		);  // remove plugin data checkbox

	}   // end function - bn_register_settings

	/**
	 * Main Settings Section Callback
	 *
	 * @since 0.0.1
	 */
	public function bn_settings_section_callback() {
	}

	/**
	 * Settings Field - title callback method
	 * option => bn_settings[news_title]
	 *
	 * @since 0.0.1
	 */
	public function bn_title_callback() {
		?>
        <input class="bn-settings-field" name="bn_settings[news_title]" id="bn_news_title" type="text"
               value="<?php echo $this->bn_settings['news_title'] ?? ''; ?>"/>
        <small class="bn-field-description">Enter Breaking news title.</small>
		<?php
	}

	/**
	 * Settings Field - Background Color Callback method
	 * option => bn_settings[background_color]
	 *
	 * @since 0.0.1
	 */
	public function bn_bg_color_callback() {
		?>
        <input class="bn-settings-field" name="bn_settings[background_color]" id="bn_background_color" type="color"
               value="<?php echo $this->bn_settings['background_color'] ?? ''; ?>"/>
        <small class="bn-field-description">Select Background Color.</small>
		<?php
	}

	/**
	 * Settings Field - Text Color Callback method
	 * option => bn_settings[text_color]
	 *
	 * @since 0.0.1
	 */
	public function bn_text_color_callback() {
		?>
        <input class="bn-settings-field" name="bn_settings[text_color]" id="bn_text_color" type="color"
               value="<?php echo $this->bn_settings['text_color'] ?? ''; ?>"/>
        <small class="bn-field-description">Select Text Color.</small>
		<?php
	}

	/**
	 * Settings Field - Selected breaking News Post Callback method
	 * option => bn_settings[post_id]
	 *
	 * @since 0.0.1
	 */
	public function bn_selected_post_callback() {
	    if( isset( $this->bn_settings['post_id'] ) ) {
            ?>
            <input class="bn-settings-field" name="bn_settings[post_id]" id="bn_post_id" type="hidden"
                   value="<?php echo $this->bn_settings['post_id'] ?? ''; ?>"/>
            <p><?php echo get_the_title( $this->bn_settings['post_id'] );?></p>
            <a class="bn-field-description" href="<?php echo get_the_permalink( $this->bn_settings['post_id'] );?>">Edit</a>
            <?php
	    } else {
	        ?>
            <p>No Post selected
                <a class="bn-field-description" href="<?php echo admin_url('edit.php');?>">click here</a>
                to select a post
            </p>
		    <?php
        }
	}

	/**
	 * Settings Field - Selected breaking News Position Callback method
	 * option => bn_settings[bn_position]
	 *
	 * @since 0.2.0
	 */
	public function bn_selected_position_callback() {
		$value = $this->bn_settings['bn_position'] ?? '';
	    ?>
        <select name="bn_settings[bn_position]" class="bn-settings-field">
            <option value="after-header" <?php selected( $value, 'after-header' ) ?>>After Header</option>
            <option value="before-header" <?php selected( $value, 'before-header' ) ?>>Before Header</option>
        </select>
        <?php
    }

	/**
	 * Settings Field - Remove Plugin Data  Callback method
	 * option => bn_settings[remove_data]
	 *
	 * @since 0.0.3
	 */
	public function bn_remove_data_callback() {
	    ?>
        <label class="bn-checkbox">
            <input name="bn_settings[remove_data]" id="remove-data" type="checkbox" <?php checked( isset( $this->bn_settings['remove_data'] ) ); ?> />
            <span class="slider round"></span>
        </label>
        <small class="bn-field-description">Enable to delete plugin data on Uninstall.</small>
		<?php
    }


	/**
	 * Sanitize Settings Fields Before Saving Data
	 *
	 * @param array $options
	 *
	 * @return array
	 * @since 0.0.1
	 */
	public function sanitize_settings_fields( array $options ): array {
		foreach ( $options as $key => $value ) {
			$options[ $key ] = sanitize_text_field( $value );
		}

		return $options;
	}
}