<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2020 Sniper_E - https://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sniper\mobiledevice;

use phpbb\extension\base;

class ext extends base
{
	public function is_enableable()
	{
		$config = $this->container->get('config');
				return phpbb_version_compare($config['version'], '3.2', '>=');
	}
}
