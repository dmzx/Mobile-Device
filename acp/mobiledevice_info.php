<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2021 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb-test.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sniper\mobiledevice\acp;

class mobiledevice_info
{
	function module()
	{
		return [
			'filename'	=> '\sniper\mobiledevice\acp\mobiledevice_module',
			'title'		=> 'ACP_MOBILEDEVICE',
			'modes'		=> [
			'config'	=> ['title' => 'ACP_MOBILEDEVICE_CONFIG_SETTINGS', 'auth' => 'ext_sniper/mobiledevice && acl_a_board', 'cat' => ['ACP_MOBILEDEVICE']],
			],
		];
	}
}
