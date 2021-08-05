<?php
/**
*
* Mobile Device. An extension for the phpBB Forum Software package.
* @copyright (c) 2015-2021 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb-test.com
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sniper\mobiledevice\acp;

class mobiledevice_module
{
	public $u_action;

	function main($id, $mode)
	{
		global $phpbb_container, $user;

		// Add the ACP lang file
		$user->add_lang_ext('sniper/mobiledevice', 'acp_mobiledevice');

		// Get an instance of the admin controller
		$admin_controller = $phpbb_container->get('sniper.mobiledevice.admin.controller');

		// Make the $u_action url available in the admin controller
		$admin_controller->set_page_url($this->u_action);

		switch ($mode)
		{
			case 'config':
				// Load a template from adm/style for our ACP page
				$this->tpl_name = 'acp_mobiledevice_config';
				// Set the page title for our ACP page
				$this->page_title = $user->lang('ACP_MOBILEDEVICE_SETTINGS');
				// Load the display options handle in the admin controller
				$admin_controller->display_options();
			break;
		}
	}
}
