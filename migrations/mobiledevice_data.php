<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2021 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb-test.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sniper\mobiledevice\migrations;

use phpbb\db\migration\migration;

class mobiledevice_data extends migration
{
	var $ext_version = '1.0.5';

	public function update_data()
	{
		return array(
		 // Add configs
		 array('config.add', array('mobile_enable', '1')),
		 array('config.add', array('mobile_welcome_enable', '1')),
		 array('config.add', array('mobile_welcome_guest_enable', '1')),
		 array('config.add', array('mobile_header_enable', '1')),
		 array('config.add', array('mobile_profile_enable', '1')),
		 array('config.add', array('mobile_logs_enable', '1')),
		 array('config.add', array('mobile_logs_refresh', 30)),
		 array('config.add', array('mobiledevice_version', $this->ext_version)),
		 // Add permissions
		 array('permission.add', array('u_mobile_logs_view')),
		 array('permission.add', array('u_mobile_logs_clear')),
		 // Set permissions
		 array('permission.permission_set', array('REGISTERED', 'u_mobile_logs_view', 'group')),
		 array('permission.permission_set', array('GUESTS', 'u_mobile_logs_view', 'group')),
		 array('permission.permission_set', array('ADMINISTRATORS', 'u_mobile_logs_clear', 'group')),
		);
	}
}
