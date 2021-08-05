<?php
/**
*
* Mobile Device. An extension for the phpBB Forum Software package.
* @copyright (c) 2015-2021 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb-test.com
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sniper\mobiledevice\controller;

use phpbb\config\config;
use phpbb\log\log;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;

class admin_controller
{
	/** @var config */
	protected $config;

	/** @var template */
	protected $template;

	/** @var log */
	protected $log;

	/** @var user */
	protected $user;

	/** @var request */
	protected $request;

	/** @var string Custom form action */
	protected $u_action;

	/**
	 * Constructor
	 *
	 * @param config		$config
	 * @param template		$template
	 * @param log 			$log
	 * @param user			$user
	 * @param request		$request
	 */
	public function __construct(
		config $config,
		template $template,
		log $log,
		user $user,
		request $request
	)
	{
		$this->config	= $config;
		$this->template = $template;
		$this->log		= $log;
		$this->user		= $user;
		$this->request	= $request;
	}

	/**
	* Display the options a user can configure for this extension
	*
	* @return null
	* @access public
	*/
	public function display_options()
	{
		add_form_key('acp_mobiledevice_config');

		// Is the form being submitted to us?
		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('acp_mobiledevice_config'))
			{
				trigger_error('FORM_INVALID');
			}

			// Set the options the user configured
			$this->set_options();

			// Add option settings change action to the admin log
			$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_ACP_MOBILEDEVICE_SETTINGS_SAVED');

			trigger_error($this->user->lang('ACP_MOBILEDEVICE_CONFIG_SAVED') . adm_back_link($this->u_action));
		}

		$this->template->assign_vars([
			'U_ACTION'						=> $this->u_action,
			'ACP_MOBILE_ENABLE'				=> $this->config['mobile_enable'],
			'ACP_MOBILE_TEST_ENABLE'			=> $this->config['mobile_test_enable'],
			'ACP_MOBILE_LOGS_REFRESH'		 => $this->config['mobile_logs_refresh'],
			'ACP_MOBILE_WELCOME_ENABLE'		=> $this->config['mobile_welcome_enable'],
			'ACP_MOBILE_WELCOME_GUEST_ENABLE' => $this->config['mobile_welcome_guest_enable'],
			'ACP_MOBILE_HEADER_ENABLE'		=> $this->config['mobile_header_enable'],
			'ACP_MOBILE_PROFILE_ENABLE'		=> $this->config['mobile_profile_enable'],
			'ACP_MOBILE_LOGS_ENABLE'			=> $this->config['mobile_logs_enable'],
			'ACP_MOBILEDEVICE_VERSION'		=> $this->config['mobiledevice_version'],
		]);
	}

	/**
	* Set the options a user can configure
	*
	* @return null
	* @access protected
	*/
	protected function set_options()
	{
		$this->config->set('mobile_enable', $this->request->variable('mobile_enable', 1));
		$this->config->set('mobile_test_enable', $this->request->variable('mobile_test_enable', 1));
		$this->config->set('mobile_logs_refresh', (int) $this->request->variable('mobile_logs_refresh', ''));
		$this->config->set('mobile_welcome_enable', $this->request->variable('mobile_welcome_enable', 1));
		$this->config->set('mobile_welcome_guest_enable', $this->request->variable('mobile_welcome_guest_enable', 1));
		$this->config->set('mobile_header_enable', $this->request->variable('mobile_header_enable', 1));
		$this->config->set('mobile_profile_enable', $this->request->variable('mobile_profile_enable', 1));
		$this->config->set('mobile_logs_enable', $this->request->variable('mobile_logs_enable', 1));
	}

	/**
	* Set page url
	*
	* @param string $u_action Custom form action
	* @return null
	* @access public
	*/
	public function set_page_url($u_action)
	{
		$this->u_action = $u_action;
	}
}
