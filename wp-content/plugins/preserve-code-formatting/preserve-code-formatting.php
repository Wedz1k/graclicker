<?php
/**
 * Plugin Name: Preserve Code Formatting
 * Version:     4.0.1
 * Plugin URI:  https://coffee2code.com/wp-plugins/preserve-code-formatting/
 * Author:      Scott Reilly
 * Author URI:  https://coffee2code.com/
 * Text Domain: preserve-code-formatting
 * License:     GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Description: Preserve formatting of code for display by preventing its modification by WordPress and other plugins while also retaining whitespace.
 *
 * NOTE: Use of the visual text editor will pose problems as it can mangle your intent in terms of <code> tags. I do not
 * offer any support for those who have the visual editor active.
 *
 * Compatible with WordPress 4.9+ through 5.7+.
 *
 * =>> Read the accompanying readme.txt file for instructions and documentation.
 * =>> Also, visit the plugin's homepage for additional information and updates.
 * =>> Or visit: https://wordpress.org/plugins/preserve-code-formatting/
 *
 * @package Preserve_Code_Formatting
 * @author  Scott Reilly
 * @version 4.0.1
 */

/*
	Copyright (c) 2004-2021 by Scott Reilly (aka coffee2code)

	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

defined( 'ABSPATH' ) or die();

if ( ! class_exists( 'c2c_PreserveCodeFormatting' ) ) :

require_once( dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'c2c-plugin.php' );

final class c2c_PreserveCodeFormatting extends c2c_Plugin_061 {
	/**
	 * Name of plugin's setting.
	 *
	 * @var string
	 */
	const SETTING_NAME = 'c2c_preserve_code_formatting';

	/**
	 * The one true instance.
	 *
	 * @var c2c_PreserveCodeFormatting
	 * @access private
	 */
	private static $instance;

	/**
	 * The chunk split token.
	 *
	 * @var string
	 * @access private
	 */
	private $chunk_split_token = '{[&*&]}';

	/**
	 * Get singleton instance.
	 *
	 * @since 3.5
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		parent::__construct( '4.0.1', 'preserve-code-formatting', 'c2c', __FILE__, array() );
		register_activation_hook( __FILE__, array( __CLASS__, 'activation' ) );

		return self::$instance = $this;
	}

	/**
	 * Handles activation tasks, such as registering the uninstall hook.
	 *
	 * @since 3.1
	 */
	public static function activation() {
		register_uninstall_hook( __FILE__, array( __CLASS__, 'uninstall' ) );
	}

	/**
	 * Handles uninstallation tasks, such as deleting plugin options.
	 *
	 * @since 3.1
	 */
	public static function uninstall() {
		delete_option( self::SETTING_NAME );
	}

	/**
	 * Initializes the plugin's configuration and localizable text variables.
	 */
	public function load_config() {
		$this->name      = __( 'Preserve Code Formatting', 'preserve-code-formatting' );
		$this->menu_name = __( 'Code Formatting', 'preserve-code-formatting' );

		$this->config = array(
			'preserve_tags' => array(
				'input'    => 'text',
				'default'  => array( 'code', 'pre' ),
				'datatype' => 'array',
				'label'    => __( 'Tags that will have their contents preserved', 'preserve-code-formatting' ),
				'help'     => __( 'Space and/or comma-separated list of HTML tag names.', 'preserve-code-formatting' ),
			),
			'preserve_in_posts' => array(
				'input'    => 'checkbox',
				'default'  => true,
				'label'    => __( 'Preserve code in posts?', 'preserve-code-formatting' ),
				'help'     => __( 'Preserve code included in posts/pages?', 'preserve-code-formatting' ),
			),
			'preserve_in_comments' => array(
				'input'    => 'checkbox',
				'default'  => true,
				'label'    => __( 'Preserve code in comments?', 'preserve-code-formatting' ),
				'help'     => __( 'Preserve code posted by visitors in comments?', 'preserve-code-formatting' ),
			),
			'wrap_multiline_code_in_pre' => array(
				'input'    => 'checkbox',
				'default'  => true,
				'label'    => __( 'Wrap multiline code in <code>&lt;pre></code> tag?', 'preserve-code-formatting' ),
				'help'     => __( '&lt;pre> helps to preserve whitespace', 'preserve-code-formatting' ),
			),
			'use_nbsp_for_spaces' => array(
				'input'    => 'checkbox',
				'default'  => true,
				'label'    => __( 'Use <code>&amp;nbsp;</code> for spaces?', 'preserve-code-formatting' ),
				'help'     => __( 'Not necessary if you are wrapping code in <code>&lt;pre></code> or you use CSS to define whitespace:pre; for code tags.', 'preserve-code-formatting' ),
			),
			'nl2br' => array(
				'input'    => 'checkbox',
				'default'  => false,
				'label'    => __( 'Convert newlines to <code>&lt;br/></code>?', 'preserve-code-formatting' ),
				'help'     => __( 'Depending on your CSS styling, you may need this. Otherwise, code may appear double-spaced.', 'preserve-code-formatting' ),
			),
		);
	}

	/**
	 * Returns translated strings used by c2c_Plugin parent class.
	 *
	 * @since 4.0
	 *
	 * @param string $string Optional. The string whose translation should be
	 *                       returned, or an empty string to return all strings.
	 *                       Default ''.
	 * @return string|string[] The translated string, or if a string was provided
	 *                         but a translation was not found then the original
	 *                         string, or an array of all strings if $string is ''.
	 */
	public function get_c2c_string( $string = '' ) {
		$strings = array(
			'A value is required for: "%s"'
				/* translators: %s: Label for setting. */
				=> __( 'A value is required for: "%s"', 'preserve-code-formatting' ),
			'Click for more help on this plugin'
				=> __( 'Click for more help on this plugin', 'preserve-code-formatting' ),
			' (especially check out the "Other Notes" tab, if present)'
				=> __( ' (especially check out the "Other Notes" tab, if present)', 'preserve-code-formatting' ),
			'Coffee fuels my coding.'
				=> __( 'Coffee fuels my coding.', 'preserve-code-formatting' ),
			'Did you find this plugin useful?'
				=> __( 'Did you find this plugin useful?', 'preserve-code-formatting' ),
			'Donate'
				=> __( 'Donate', 'preserve-code-formatting' ),
			'Expected integer value for: %s'
				=> __( 'Expected integer value for: %s', 'preserve-code-formatting' ),
			'Invalid file specified for C2C_Plugin: %s'
				/* translators: %s: Path to the plugin file. */
				=> __( 'Invalid file specified for C2C_Plugin: %s', 'preserve-code-formatting' ),
			'More information about %1$s %2$s'
				/* translators: 1: plugin name 2: plugin version */
				=> __( 'More information about %1$s %2$s', 'preserve-code-formatting' ),
			'More Help'
				=> __( 'More Help', 'preserve-code-formatting' ),
			'More Plugin Help'
				=> __( 'More Plugin Help', 'preserve-code-formatting' ),
			'Please consider a donation'
				=> __( 'Please consider a donation', 'preserve-code-formatting' ),
			'Reset Settings'
				=> __( 'Reset Settings', 'preserve-code-formatting' ),
			'Save Changes'
				=> __( 'Save Changes', 'preserve-code-formatting' ),
			'See the "Help" link to the top-right of the page for more help.'
				=> __( 'See the "Help" link to the top-right of the page for more help.', 'preserve-code-formatting' ),
			'Settings'
				=> __( 'Settings', 'preserve-code-formatting' ),
			'Settings reset.'
				=> __( 'Settings reset.', 'preserve-code-formatting' ),
			'Something went wrong.'
				=> __( 'Something went wrong.', 'preserve-code-formatting' ),
			'The plugin author homepage.'
				=> __( 'The plugin author homepage.', 'preserve-code-formatting' ),
			"The plugin configuration option '%s' must be supplied."
				/* translators: %s: The setting configuration key name. */
				=>__( "The plugin configuration option '%s' must be supplied.", 'preserve-code-formatting' ),
			'This plugin brought to you by %s.'
				/* translators: %s: Link to plugin author's homepage. */
				=> __( 'This plugin brought to you by %s.', 'preserve-code-formatting' ),
		);

		if ( ! $string ) {
			return array_values( $strings );
		}

		return ! empty( $strings[ $string ] ) ? $strings[ $string ] : $string;
	}

	/**
	 * Override the plugin framework's register_filters() to register actions and
	 * filters.
	 */
	public function register_filters() {
		$options = $this->get_options();

		if ( $options['preserve_in_posts'] ) {
			add_filter( 'the_content',             array( $this, 'preserve_preprocess' ), 2 );
			add_filter( 'the_content',             array( $this, 'preserve_postprocess_and_preserve'), 100 );
			add_filter( 'content_save_pre',        array( $this, 'preserve_preprocess' ), 2 );
			add_filter( 'content_save_pre',        array( $this, 'preserve_postprocess' ), 100 );

			add_filter( 'the_excerpt',             array( $this, 'preserve_preprocess' ), 2 );
			add_filter( 'the_excerpt',             array( $this, 'preserve_postprocess_and_preserve' ), 100 );
			add_filter( 'excerpt_save_pre',        array( $this, 'preserve_preprocess' ), 2 );
			add_filter( 'excerpt_save_pre',        array( $this, 'preserve_postprocess' ), 100 );
		}

		if ( $options['preserve_in_comments'] ) {
			add_filter( 'comment_text',            array( $this, 'preserve_preprocess' ), 2 );
			add_filter( 'comment_text',            array( $this, 'preserve_postprocess_and_preserve' ), 100 );
			add_filter( 'pre_comment_content',     array( $this, 'preserve_preprocess' ), 2 );
			add_filter( 'pre_comment_content',     array( $this, 'preserve_postprocess' ), 100 );
		}
	}

	/**
	 * Outputs the text above the setting form.
	 *
	 * @param string $localized_heading_text Optional. Localized page heading
	 *                                       text. Default ''.
	 */
	public function options_page_description( $localized_heading_text = '' ) {
		$options = $this->get_options();
		parent::options_page_description( __( 'Preserve Code Formatting Settings', 'preserve-code-formatting' ) );
		echo '<p>' . __( 'Preserve formatting for text within &lt;code> and &lt;pre> tags (other tags can be defined as well). Helps to preserve code indentation, multiple spaces, prevents WP\'s fancification of text (ie. ensures quotes don\'t become curly, etc).', 'preserve-code-formatting' ) . '</p>';
		echo '<p>' . __( 'NOTE: Use of the visual text editor will pose problems as it can mangle your intent in terms of &lt;code> tags. I do not offer any support for those who have the visual editor active.', 'preserve-code-formatting' ) . '</p>';
	}

	/**
	 * Preps code.
	 *
	 * @param  string $text Text to prep.
	 * @return string The prepped text.
	 */
	public function prep_code( $text ) {
		$options = $this->get_options();

		$text = preg_replace( "/(\r\n|\n|\r)/", "\n", $text );
		$text = preg_replace( "/\n\n+/", "\n\n", $text );
		$text = str_replace( array( "&#36&;", "&#39&;" ), array( "$", "'" ), $text );
		$text = htmlspecialchars( $text, ENT_QUOTES );
		$text = str_replace( "\t", '  ', $text );

		if ( $options['use_nbsp_for_spaces'] ) {
			$text = str_replace( '  ', '&nbsp;&nbsp;', $text );
		}

		if ( $options['nl2br'] ) {
			$text = nl2br( $text );
		}

		return $text;
	}

	/**
	 * Preserves the code formatting for text.
	 *
	 * @param  string $text Text with code formatting to preserve.
	 * @return string The text with code formatting preserved.
	 */
	public function preserve_code_formatting( $text ) {
		$text = str_replace( array( '$', "'" ), array( '&#36&;', '&#39&;' ), $text );
		$text = $this->prep_code( $text );
		$text = str_replace( array( '&#36&;', '&#39&;', '&lt; ?php' ), array( '$', "'", '&lt;?php' ), $text );

		return $text;
	}

	/**
	 * Preprocessor for code formatting preservation process.
	 *
	 * @param  string $content Text with code formatting to preserve.
	 * @return string The text with code formatting preprocessed.
	 */
	public function preserve_preprocess( $content ) {
		if ( has_block( 'code', $content ) ) {
			return $content;
		}

		$options       = $this->get_options();
		$preserve_tags = (array) $options['preserve_tags'];
		$result        = '';

		foreach ( $preserve_tags as $tag ) {
			if ( $result ) {
				$content = $result;
				$result = '';
			}

			$codes = preg_split( "/(<{$tag}[^>]*>.*<\\/{$tag}>)/Us", $content, -1, PREG_SPLIT_DELIM_CAPTURE );

			foreach ( $codes as $code ) {
				if ( preg_match( "/^<({$tag}[^>]*)>(.*)<\\/{$tag}>/Us", $code, $match ) ) {
					$code = "{!{{$match[1]}}!}";
					// Note: base64_encode is only being used to encode user-supplied content of code tags which
					// will be decoded later in the filtering process to prevent modification by WP.
					$code .= base64_encode( addslashes( chunk_split( serialize( $match[2] ), 76, $this->chunk_split_token ) ) );
					$code .= "{!{/{$tag}}!}";
				}
				$result .= $code;
			}
		}

		return $result;
	}

	/**
	 * Post-processor for code formatting preservation process.
	 *
	 * @param  string $content  Text that was preprocessed for code formatting.
	 * @param  bool   $preserve Optional. Preserve? Default false.
	 * @return string The text with code formatting post-processed.
	 */
	public function preserve_postprocess( $content, $preserve = false ) {
		$options                    = $this->get_options();
		$preserve_tags              = (array) $options['preserve_tags'];
		$wrap_multiline_code_in_pre = (bool)  $options['wrap_multiline_code_in_pre'];
		$result                     = '';

		foreach ( $preserve_tags as $tag ) {
			if ( $result ) {
				$content = $result;
				$result = '';
			}

			$codes = preg_split( "/(\\{\\!\\{{$tag}[^\\]]*\\}\\!\\}.*\\{\\!\\{\\/{$tag}\\}\\!\\})/Us", $content, -1, PREG_SPLIT_DELIM_CAPTURE );

			foreach ( $codes as $code ) {
				if ( preg_match( "/\\{\\!\\{({$tag}[^\\]]*)\\}\\!\\}(.*)\\{\\!\\{\\/{$tag}\\}\\!\\}/Us", $code, $match ) ) {
					// Note: base64_decode is only being used to decode user-supplied content of code tags which
					// had been encoded earlier in the filtering process to prevent modification by WP.
					$data = unserialize( str_replace( $this->chunk_split_token, '', stripslashes( base64_decode( $match[2] ) ) ) );
					if ( $preserve ) {
						$data = $this->preserve_code_formatting( $data );
					}
					$code = "<{$match[1]}>$data</$tag>";
					if ( $preserve && $wrap_multiline_code_in_pre && ( 'pre' != $tag ) && preg_match( "/\n/", $data ) ) {
						$code = '<pre>' . $code . '</pre>';
					}
				}
				$result .= $code;
			}
		}

		return $result;
	}

	/**
	 * Post-processor for code formatting preservation process that defaults to
	 * true for preserving.
	 *
	 * @param  string $content Text with code formatting to post-process and preserve.
	 * @return string The text with code formatting post-processed and preserved.
	 */
	public function preserve_postprocess_and_preserve( $content ) {
		return $this->preserve_postprocess( $content, true );
	}

} // end c2c_PreserveCodeFormatting

add_action( 'plugins_loaded', array( 'c2c_PreserveCodeFormatting', 'get_instance' ) );

endif; // end if !class_exists()
