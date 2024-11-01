<?php 

if (!defined('KLICK_UAL_PLUGIN_MAIN_PATH')) die('No direct access allowed');

/**
 * Commands available from control interface (e.g. wp-admin) are here
 * All public methods should either return the data, or a WP_Error with associated error code, message and error data
 */
/**
 * Sub commands for Ajax
 *
 */
class Klick_Ual_Commands {
	private $options;
	
	/**
	 * Constructor for Commands class
	 *
	 */
	public function __construct() {
		$this->options = Klick_Ual()->get_options();
	}
	/**
	 * This sends the passed data value over to the save function
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array    $status
	 */
	public function klick_ual_save_settings($data) {
		parse_str(stripslashes($data), $posted_settings);
		return array(
			'status' => $this->options->save_settings($posted_settings),
			);
	}
	
	/**
	 * dis-miss button
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$status
	 */
	public function dismiss_page_notice_until($data) {
		
		return array(
			'status' => $this->options->dismiss_page_notice_until($data),
			);
	}

	/**
	 * dis-miss button
	 *
	 * @param  Array 	$data an array of data UI form
	 *
	 * @return Array 	$status
	 */
	public function dismiss_page_notice_until_forever($data) {
		
		return array(
			'status' => $this->options->dismiss_page_notice_until_forever($data),
			);
	}
}
