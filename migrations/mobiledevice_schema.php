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

class mobiledevice_schema extends migration
{
	public function update_schema()
	{
		return 	[
			'add_columns' => [
				$this->table_prefix . 'users' => [
					'mobile_browser'		=> ['UINT:1', 0],
					'device_name'		 => ['VCHAR:30', null],
					'user_mobile_welcome' => ['BOOL', 1],
					'user_mobile_header'	=> ['BOOL', 1],
					'user_mobile_self'	=> ['BOOL', 1],
				],
				$this->table_prefix . 'posts' => [
					'post_device_title' => ['VCHAR:30', null],
				],
			],
			'add_tables'	=> [
				$this->table_prefix . 'mobile_logs'	=> [
					'COLUMNS' => [
					'log_id'		=> ['UINT', null, 'auto_increment'],
					'log_ip'		=> ['VCHAR:15', ''],
					'log_time'	=> ['INT:11', 0],
					'device_name' => ['VCHAR:30', ''],
					'user_name'	=> ['VCHAR:30', ''],
					'user_agent'	=> ['VCHAR:255', ''],
					],
					'PRIMARY_KEY' => 'log_id',
				],
			],
		];
	}

	public function revert_schema()
	{
		return 	[
			'drop_columns' => [
				$this->table_prefix . 'users' => [
					'mobile_browser',
					'device_name',
					'user_mobile_welcome',
					'user_mobile_header',
					'user_mobile_self',
				],
				$this->table_prefix . 'posts' => [
					'post_device_title',
				],
			],
			'drop_tables' => [
				$this->table_prefix . 'mobile_logs',
			],
		];
	}
}
