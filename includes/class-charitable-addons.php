<?php
/**
 * Class to load addon functionality.
 *
 * @package		Charitable/Classes/Charitable_Addons
 * @version 	1.0.0
 * @author 		Eric Daams
 * @copyright 	Copyright (c) 2014, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Addons' ) ) : 

/**
 * Charitable_Addons
 *
 * @since 		1.0.0
 */
class Charitable_Addons {

	/**
	 * @var 	Charitable 		$charitable
	 * @access 	private
	 */
	private $charitable;

	/**
	 * Create class instane. 
	 *
	 * @param 	Charitable 		$charitable
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function charitable_start( Charitable $charitable ) {
		if ( ! $charitable->is_start() ) {
			return;
		}

		new Charitable_Addons( $charitable );
	}

	/**
	 * Create class object.
	 * 
	 * @param 	Charitable 		$charitable
	 * @access 	private
	 * @since	1.0.0
	 */
	private function __construct( Charitable $charitable ) {
		$this->charitable = $charitable;	

		add_action('charitable_activate_addon', array( $this, 'activate_addon' ) );
		add_action('plugins_loaded',			array( $this, 'load_addons' ), 100 );
	}

	/**
	 * Activate an addon. 
	 *
	 * This is programatically called on the charitable_activate_addon hook, 
	 * triggered by a plugin. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function activate_addon( $addon ) {
		/* This method should only be called on the charitable_activate_addon hook */
		if ( 'charitable_activate_addon' !== current_filter() ) {
			return;
		}

		$filepath = $this->get_addon_filepath( $addon ); 

		/* If we cannot read the file, bounce back with an error. */
		if ( ! file_exists( $filepath ) || ! is_readable( $filepath ) ) {
			_doing_it_wrong( __METHOD__, sprintf( 'File %s does not exist or is not readable', $filepath ), '1.0.0' );
			return;
		}

		$this->load_addon_dependencies();	

		require_once( $filepath );

		$class = $this->get_addon_class( $addon );
		
		/* Call the Addon's activate method */
		call_user_func( array( $class, 'activate' ) );
	}

	/**
	 * Load activated addons. 
	 *
	 * @return 	void
	 * @access  public
	 * @since 	1.0.0
	 */
	public function load_addons() {
		$active_addons = apply_filters( 'charitable_active_addons', array() );

		if ( empty( $active_addons ) ) {
			return;
		}

		$this->load_addon_dependencies();		

		foreach ( $active_addons as $addon ) {

			require_once( $this->get_addon_filepath( $addon ) );

			/* Call the Addon's load method */
			call_user_func( array( $this->get_addon_class( $addon ), 'load' ) );
		}	
	}

	/**
	 * Load interface and abstract classes that addons use. 
	 *
	 * @return 	void
	 * @access  private
	 * @since 	1.0.0
	 */
	private function load_addon_dependencies() {
		require_once( $this->charitable->get_path( 'includes' ) . 'addons/interface-charitable-addon.php' );
	}

	/**
	 * Return the filepath to the given addon. 
	 *
	 * @param 	string 		$addon
	 * @return 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private function get_addon_filepath( $addon ) {
		return $this->charitable->get_path( 'includes' ) . "addons/{$addon}/class-{$addon}.php";
	}

	/**
	 * Get class name of addon. 
	 *
	 * @param 	string 		$addon
	 * @return 	string
	 * @access  private
	 * @since 	1.0.0
	 */
	private function get_addon_class( $addon ) {
		$class = str_replace( '-', ' ', $addon );
		$class = ucfirst( $class );
		$class = str_replace( ' ', '_', $class );
		return $class;
	}
}

endif; // End class_exists check