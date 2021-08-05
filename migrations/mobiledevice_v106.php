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

class mobiledevice_v106 extends migration
{
	static public function depends_on()
	{
		return array(
			'\sniper\mobiledevice\migrations\mobiledevice_data',
		);
	}

	public function update_data()
	{
		return array(
			// Update config
			array('config.update', array('mobiledevice_version', '1.0.6')),
			// Add config
			array('config.add', array('mobile_test_enable', '0')),
		);
	}
}
