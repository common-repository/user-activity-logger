<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (class_exists('Klick_Ual_EMAIL_Logger')) return;

/**
 * Class Klick_Ual_EMAIL_Logger
 */
class Klick_Ual_EMAIL_Logger extends Klick_Ual_Abstract_Logger {

	public $id = "email";

	public $additiona_params = array();

	/**
	 * Klick_Ual_EMAIL_Logger constructor
	 */
	public function __construct() {
	}

	/**
	 * Returns logger description
	 *
	 * @return string|void
	 */
	public function get_description() {
		return __('Log events into Email address', 'klick-ual');
	}
	
	/**
	 * Log message with any level
	 *
	 * @param  mixed  $level
	 * @param  string $message
	 *
	 * @return void
	 */
	public function log($level, $message) {

		if (!$this->is_enabled()) return false;
		
		$message = 'From email[' . $level . '] : ' . $message;
		$this-> send_mail($level, $message);
	}

	/**
	 * Sends mail
	 *
	 * @param  mixed  $level
	 * @param  string $message
	 *
	 * @return void
	 */
	public function send_mail($level, $message) {
		wp_mail($this->additiona_params['email'], $level,$message);	
	}
}
