<?php
/**
*
* Mobile Device. An extension for the phpBB Forum Software package.
* @copyright (c) 2015-2021 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb-test.com
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sniper\mobiledevice\migrations;

use phpbb\db\migration\migration;

class mobiledevice_data extends migration
{
	var $ext_version = '1.0.5';

	public function update_data()
	{
		return [
		 // Add configs
		 ['config.add', ['mobile_enable', '1']],
		 ['config.add', ['mobile_welcome_enable', '1']],
		 ['config.add', ['mobile_welcome_guest_enable', '1']],
		 ['config.add', ['mobile_header_enable', '1']],
		 ['config.add', ['mobile_profile_enable', '1']],
		 ['config.add', ['mobile_logs_enable', '1']],
		 ['config.add', ['mobile_logs_refresh', 30]],
		 ['config.add', ['mobiledevice_version', $this->ext_version]],
		 // Add permissions
		 ['permission.add', ['u_mobile_logs_view']],
		 ['permission.add', ['u_mobile_logs_clear']],
		 // Set permissions
		 ['permission.permission_set', ['REGISTERED', 'u_mobile_logs_view', 'group']],
		 ['permission.permission_set', ['GUESTS', 'u_mobile_logs_view', 'group']],
		 ['permission.permission_set', ['ADMINISTRATORS', 'u_mobile_logs_clear', 'group']],
		];
	}
}
