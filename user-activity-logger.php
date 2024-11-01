<?php

/**
Plugin Name: User Activity Logger
Description: WP plugin to send mail or track access on your site.
Version: 0.0.2
Author: klick on it
Author URI: http://klick-on-it.com
License: GPLv2 or later
Text Domain: klick-ual
 */

/*
This plugin developed by klick-on-it.com
*/

/*
Copyright 2017 klick on it (http://klick-on-it.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License (Version 3 - GPLv3)
as published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if (!defined('ABSPATH')) die('No direct access allowed');

if (!class_exists('Klick_Ual')) :
define('KLICK_UAL_VERSION', '0.0.1');
define('KLICK_UAL_PLUGIN_URL', plugin_dir_url(__FILE__));
define('KLICK_UAL_PLUGIN_MAIN_PATH', plugin_dir_path(__FILE__));
define('KLICK_UAL_PLUGIN_SETTING_PAGE', admin_url() . 'admin.php?page=klick_ual');

class Klick_Ual {

	protected static $_instance = null;

	protected static $_options_instance = null;

	protected static $_notifier_instance = null;

	protected static $_logger_instance = null;

	protected static $_dashboard_instance = null;
	
	/**
	 * Constructor for main plugin class
	 */
	public function __construct() {
		
		register_activation_hook(__FILE__, array($this, 'klick_ual_activation_actions'));

		register_deactivation_hook(__FILE__, array($this, 'klick_ual_deactivation_actions'));

		add_action('wp_ajax_klick_ual_ajax', array($this, 'klick_ual_ajax_handler'));
		
		add_action('admin_menu', array($this, 'init_dashboard'));
		
		add_action('plugins_loaded', array($this, 'setup_translation'));
		
		add_action('plugins_loaded', array($this, 'setup_loggers'));
		
		add_action('wp_login', array($this, 'klick_ual_set_when_login'));

		add_action('wp_logout', array($this, 'klick_ual_set_when_logout'));
	}

	/**
	 * Instantiate Klick_Ual if needed
	 *
	 * @return object Klick_Ual
	 */
	public static function instance() {
		if (empty(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Instantiate Klick_Ual_Options if needed
	 *
	 * @return object Klick_Ual_Options
	 */
	public static function get_options() {
		if (empty(self::$_options_instance)) {
			if (!class_exists('Klick_Ual_Options')) include_once(KLICK_UAL_PLUGIN_MAIN_PATH . '/includes/class-klick-ual-options.php');
			self::$_options_instance = new Klick_Ual_Options();
		}
		return self::$_options_instance;
	}
	
	/**
	 * Instantiate Klick_Ual_Dashboard if needed
	 *
	 * @return object Klick_Ual_Dashboard
	 */
	public static function get_dashboard() {
		if (empty(self::$_dashboard_instance)) {
			if (!class_exists('Klick_Ual_Dashboard')) include_once(KLICK_UAL_PLUGIN_MAIN_PATH . '/includes/class-klick-ual-dashboard.php');
			self::$_dashboard_instance = new Klick_Ual_Dashboard();
		}
		return self::$_dashboard_instance;
	}
	
	/**
	 * Instantiate Klick_Ual_Logger if needed
	 *
	 * @return object Klick_Ual_Logger
	 */
	public static function get_logger() {
		if (empty(self::$_logger_instance)) {
			if (!class_exists('Klick_Ual_Logger')) include_once(KLICK_UAL_PLUGIN_MAIN_PATH . '/includes/class-klick-ual-logger.php');
			self::$_logger_instance = new Klick_Ual_Logger();
		}
		return self::$_logger_instance;
	}
	
	/**
	 * Instantiate Klick_Ual_Notifier if needed
	 *
	 * @return object Klick_Ual_Notifier
	 */
	public static function get_notifier() {
		if (empty(self::$_notifier_instance)) {
			include_once(KLICK_UAL_PLUGIN_MAIN_PATH . '/includes/class-klick-ual-notifier.php');
			self::$_notifier_instance = new Klick_Ual_Notifier();
		}
		return self::$_notifier_instance;
	}
	
	/**
	 * Establish Capibility
	 *
	 * @return string
	 */
	public function capability_required() {
		return apply_filters('klick_ual_capability_required', 'manage_options');
	}
	
	/**
	 * Init dashboard with menu and layout
	 *
	 * @return void
	 */
	public function init_dashboard() {
		$dashboard = $this->get_dashboard();
		$dashboard->init_menu();
		load_plugin_textdomain('klick-ual', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * Perform post plugin loaded setup
	 *
	 * @return void
	 */
	public function setup_translation() {
		load_plugin_textdomain('klick-ual', false, dirname(plugin_basename(__FILE__)) . '/languages');
	}

	/**
	 * Creates an array of loggers, Activate and Adds
	 *
	 * @return void
	 */
	public function setup_loggers() {
		
		$logger = $this->get_logger();

		$loggers = $logger->klick_ual_get_loggers();
		
		$logger->activate_logs($loggers);
		
		$logger->add_loggers($loggers);
	}
	
	/**
	 * Ajax Handler
	 */
	public function klick_ual_ajax_handler() {

		$nonce = empty($_POST['nonce']) ? '' : $_POST['nonce'];

		if (!wp_verify_nonce($nonce, 'klick_ual_ajax_nonce') || empty($_POST['subaction'])) die('Security check');

		$subaction = $_POST['subaction'];
		$data = isset($_POST['data']) ? $_POST['data'] : null;
		$results = array();
		
		// Get sub-action class
		if (!class_exists('Klick_Ual_Commands')) include_once(KLICK_UAL_PLUGIN_MAIN_PATH . 'includes/class-klick-ual-commands.php');

		$commands = new Klick_Ual_Commands();

		if (!method_exists($commands, $subaction)) {
			error_log("Klick-Ual-Commands: ajax_handler: no such sub-action (" . $subaction . ")");
			die('No such sub-action/command');
		} else {
			$results = call_user_func(array($commands, $subaction), $data);

			if (is_wp_error($results)) {
				$results = array(
					'result' => false,
					'error_code' => $results->get_error_code(),
					'error_message' => $results->get_error_message(),
					'error_data' => $results->get_error_data(),
					);
			}
		}
		
		echo json_encode($results);
		die;
	}

	/**
	 * Set log and send mail when user logged in
	 *
	 * @return void
	 */
	public function klick_ual_set_when_login() {
		
		$this->get_logger()->log(__("Notice", "klick-ual"),__("User Logged In", "klick-ual"), array('email'), array('email' => $this->get_options()->get_option('email')));
		// To enable php comments uncomment next line
		// $this->get_logger()->log('__("Notice", "klick-ual")', '__("User logged in for php log", "klick-ual")', array('php'));
	}

	/**
	 * Set log and send mail when user logged out
	 * 
	 * @return void
	 */
	public function klick_ual_set_when_logout() {
		$this->get_logger()->log(__("Notice", "klick-ual"),__("User Logged Out", "klick-ual"), array('email'), array('email' => $this->get_options()->get_option('email')));
	}
	
	/**
	 * Plugin activation actions.
	 *
	 * @return void
	 */
	public function klick_ual_activation_actions(){
		$this->get_options()->set_default_options();
	}

	/**
	 * Plugin deactivation actions.
	 *
	 * @return void
	 */
	public function klick_ual_deactivation_actions(){
		$this->get_options()->delete_all_options();
	}
}

register_uninstall_hook(__FILE__,'klick_ual_uninstall_option');

/**
 * Delete data when uninstall
 *
 * @return void
 */
function klick_ual_uninstall_option(){
	Klick_Ual()->get_options()->delete_all_options();
}

/**
 * Instantiates the main plugin class
 *
 * @return instance
 */
function Klick_Ual(){
     return Klick_Ual::instance();
}

endif;

$GLOBALS['Klick_Ual'] = Klick_Ual();
