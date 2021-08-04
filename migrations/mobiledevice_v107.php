<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2019 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - http://www.martins-phpbb.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sniper\mobiledevice\migrations;

class mobiledevice_v107 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return array(
			'\sniper\mobiledevice\migrations\mobiledevice_v106',
		);
	}

	public function update_data()
	{
		return array(
			// Update config
			array('config.update', array('mobiledevice_version', '1.0.7')),
		);
	}
}
