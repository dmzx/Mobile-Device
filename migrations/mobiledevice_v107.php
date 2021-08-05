<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2021 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb-test.com
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sniper\mobiledevice\migrations;

use phpbb\db\migration\migration;

class mobiledevice_v107 extends migration
{
	static public function depends_on()
	{
		return [
			'\sniper\mobiledevice\migrations\mobiledevice_v106',
		];
	}

	public function update_data()
	{
		return [
			// Update config
			['config.update', ['mobiledevice_version', '1.0.7']],
		];
	}
}
