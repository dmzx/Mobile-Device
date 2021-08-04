<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2020 Sniper_E - https://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sniper\mobiledevice\migrations;

class mobiledevice_v108 extends \phpbb\db\migration\migration
{
	static public function depends_on()
	{
		return [
			'\sniper\mobiledevice\migrations\mobiledevice_v107',
		];
	}

	public function update_data()
	{
		return [
			// Update config
			['config.update', ['mobiledevice_version', '1.0.8']],
		];
	}
}