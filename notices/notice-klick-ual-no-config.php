<?php

if (!defined('ABSPATH')) die('No direct access allowed');

if (class_exists('Klick_Ual_No_Config')) return;

require_once(KLICK_UAL_PLUGIN_MAIN_PATH . '/includes/class-klick-ual-abstract-notice.php');

/**
 * Class Klick_Ual_No_Config
 */
class Klick_Ual_No_Config extends Klick_Ual_Abstract_Notice {
	
	/**
	 * Klick_Ual_No_Config constructor
	 */
	public function __construct() {
		$this->notice_id = 'User-Activity-Logger-configure';
		$this->title = __('User Activity Logger plugin is installed but not configured', 'klick-ual');
		$this->klick_ual = "";
		$this->notice_text = __('Configure it Now', 'klick-ual');
		$this->image_url = '../images/our-more-plugins/UAL.svg';
		$this->dismiss_time = 'dismiss-page-notice-until';
		$this->dismiss_interval = 30;
		$this->display_after_time = 0;
		$this->dismiss_type = 'dismiss';
		$this->dismiss_text = __('Hide Me!', 'klick-ual');
		$this->position = 'dashboard';
		$this->only_on_this_page = 'index.php';
		$this->button_link = KLICK_UAL_PLUGIN_SETTING_PAGE;
		$this->button_text = __('Click here', 'klick-ual');
		$this->notice_template_file = 'main-dashboard-notices.php';
		$this->validity_function_param = 'user-activity-logger/user-activity-logger.php';
		$this->validity_function = 'is_plugin_configured';
	}
}
