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

class mobiledevice_module extends migration
{
	public function update_data()
	{
		return array(
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'ACP_MOBILEDEVICE'
			)),
			array('module.add', array(
				'acp',
				'ACP_MOBILEDEVICE',
				array(
					'module_basename' => '\sniper\mobiledevice\acp\mobiledevice_module',
					'modes'			=> array('config'),
				),
			)),
		);
	}
}
