<?php
/**
*
* Mobile Device. An extension for the phpBB Forum Software package.
* @copyright (c) 2015-2021 Sniper_E - https://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb.com
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sniper\mobiledevice\migrations;

use phpbb\db\migration\migration;

class mobiledevice_v109 extends migration
{
	static public function depends_on()
	{
		return [
			'\sniper\mobiledevice\migrations\mobiledevice_v108',
		];
	}

	public function update_data()
	{
		return [
			// Update config
			['config.update', ['mobiledevice_version', '1.0.9']],
		];
	}
}
