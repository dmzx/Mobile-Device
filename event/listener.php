<?php
/**
*
* Mobile Device. An extension for the phpBB Forum Software package.
* @copyright (c) 2015-2021 Sniper_E - https://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - https://www.martins-phpbb.com
* @license GNU General Public License, version 2 (GPL-2.0)
*
*/

namespace sniper\mobiledevice\event;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\request\request;
use phpbb\request\request_interface;
use phpbb\template\template;
use phpbb\user;
use sniper\mobiledevice\core\functions;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var functions */
	protected $functions;

	/** @var request */
	protected $request;

	/** @var user */
	protected $user;

	/** @var template */
	protected $template;

	/** @var driver_interface */
	protected $db;

	/** @var config */
	protected $config;

	/** @var auth */
	protected $auth;

	/** @var helper */
	protected $helper;

	/** @var string database tables */
	protected $mobilelogs_table;

	/**
	* Constructor
	* @param functions			$functions
	* @param request			$request
	* @param user				$user
	* @param template			$template
	* @param driver_interface	$db
	* @param config				$config
	* @param auth				$auth
	* @param helper				$helper
	* @param user				$user
	* @param string				$mobilelogs_table
	*/
	public function __construct(
		functions $functions,
		request $request,
		user $user,
		template $template,
		driver_interface $db,
		config $config,
		auth $auth,
		helper $helper,
		$mobilelogs_table
	)
	{
		$this->functions		= $functions;
		$this->request			= $request;
		$this->user			 	= $user;
		$this->template		 	= $template;
		$this->db				= $db;
		$this->config			= $config;
		$this->auth			 	= $auth;
		$this->helper			= $helper;
		$this->mobilelogs_table = $mobilelogs_table;
	}

	static public function getSubscribedEvents()
	{
		return [
			'core.permissions'				 	=> 'permissions',
			'core.user_setup'					=> 'load_language_on_setup',
			'core.ucp_prefs_view_data'		 	=> 'ucp_prefs_get_data',
			'core.ucp_prefs_view_update_data'	=> 'ucp_prefs_set_data',
			'core.memberlist_view_profile'	 	=> 'memberlist_view_profile',
			'core.viewtopic_post_rowset_data'	=> 'viewtopic_post_rowset_data',
			'core.viewtopic_modify_post_row'	=> 'viewtopic_modify_post_row',
			'core.submit_post_modify_sql_data' 	=> 'submit_post_modify_sql_data',
			'core.user_setup_after'				=> 'user_setup_after',
			'core.session_kill_after'			=> 'session_kill_after',
			'core.page_header'				 	=> 'page_header',
		];
	}

	public function permissions($event)
	{
		$permissions = $event['permissions'];
		$permissions['u_mobile_logs_view'] = ['lang' => 'ACL_U_MOBILE_LOGS_VIEW', 'cat' => 'misc'];
		$permissions['u_mobile_logs_clear'] = ['lang' => 'ACL_U_MOBILE_LOGS_CLEAR', 'cat' => 'misc'];
		$event['permissions'] = $permissions;
	}

	public function load_language_on_setup($event)
	{
		$lang_set_ext = $event['lang_set_ext'];
		$lang_set_ext[] = [
			'ext_name' => 'sniper/mobiledevice',
			'lang_set' => 'common',
		];
		$event['lang_set_ext'] = $lang_set_ext;
	}

	public function ucp_prefs_get_data($event)
	{
		$event['data'] = array_merge($event['data'], [
			'mobilewelcome' => $this->request->variable('mobilewelcome', (int) $this->user->data['user_mobile_welcome']),
			'mobileheader'	=> $this->request->variable('mobileheader', (int) $this->user->data['user_mobile_header']),
			'mobileself'	=> $this->request->variable('mobileself', (int) $this->user->data['user_mobile_self']),
		]);

		if (!$event['submit'])
		{
			$this->template->assign_vars([
				'S_UCP_MOBILEWELCOME' 	=> $event['data']['mobilewelcome'],
				'S_UCP_MOBILEHEADER'	=> $event['data']['mobileheader'],
				'S_UCP_MOBILESELF'		=> $event['data']['mobileself'],
			]);
		}
	}

	public function ucp_prefs_set_data($event)
	{
		$event['sql_ary'] = array_merge($event['sql_ary'], [
			'user_mobile_welcome' 	=> $event['data']['mobilewelcome'],
			'user_mobile_header'	=> $event['data']['mobileheader'],
			'user_mobile_self'		=> $event['data']['mobileself'],
		]);
	}

	public function memberlist_view_profile($event)
	{
		$member = $event['member'];

		$this->template->assign_vars([
			'S_IS_MOBILE_PROFILE'	=> ($member['mobile_browser']),
			'DEVICE_NAME_PROFILE' => ($member['device_name']),
		]);
	}

	public function viewtopic_post_rowset_data($event)
	{
		$rowset_data = $event['rowset_data'];
		$row = $event['row'];

		$rowset_data = array_merge($rowset_data, [
			'device_name'		=> $row['device_name'],
			'post_device_title' => $row['post_device_title'],
		]);

		$event['rowset_data'] = $rowset_data;
	}

	public function viewtopic_modify_post_row($event)
	{
		$row = $event['row'];
		$post_row = $event['post_row'];

		$post_row = array_merge($post_row, [
			'DEVICE_TITLE'		=> $row['device_name'],
			'POST_DEVICE_TITLE' => $row['post_device_title'],
		]);

		$event['post_row'] = $post_row;
	}

	public function submit_post_modify_sql_data($event)
	{
		$this->user->data['is_mobile'] = $this->functions->mobile_device_detect();
		$status = $this->functions->mobile_device_detect();

		if ($this->user->data['is_mobile'] && ($event['post_mode'] == 'post' || $event['post_mode'] == 'reply'))
		{
			$sql_data = $event['sql_data'];
			$sql_data[POSTS_TABLE]['sql']['post_device_title'] = $status[1];
			$event['sql_data'] = $sql_data;
		}
	}

	public function user_setup_after($event)
	{
		$this->user->data['is_mobile'] = $this->functions->mobile_device_detect();
		$status = $this->functions->mobile_device_detect();
		$this->cookie_data['mobile_name'] =	$status[1];
		$cookie_mobile_name = $this->request->variable($this->config['cookie_name'] . '_mobile_name', '', true, request_interface::COOKIE);

		if (!$cookie_mobile_name)
		{
			$this->user->set_cookie('mobile_name', $status[1], time() + 5 * 24 * 60 * 60, '/', false, false);
		}

		if ($this->user->data['is_mobile'])
		{
			$mobile_browser = $this->request->variable('mobile_browser', 1);
			$device_name = $this->request->variable('device_name', $status[1]);

			$user_array = [
				'mobile_browser' 	=> (string) $mobile_browser,
				'device_name' 		=> (string) $device_name
			];

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $user_array) . '
				WHERE user_id = ' . (int) $this->user->data['user_id'];
			$this->db->sql_query($sql);
		}
		else
		{
			$mobile_browser	= $this->request->variable('mobile_browser', 0);
			$device_name = $this->request->variable('device_name', '');

			$user_array = [
				'mobile_browser' 	=> (string) $mobile_browser,
				'device_name' 		=> (string) $device_name
			];

			$sql = 'UPDATE ' . USERS_TABLE . '
				SET ' . $this->db->sql_build_array('UPDATE', $user_array) . '
				WHERE user_id = ' . (int) $this->user->data['user_id'];
			$this->db->sql_query($sql);
		}
	}

	public function session_kill_after($event)
	{
		$mobile_browser	= $this->request->variable('mobile_browser', 0);
		$device_name = $this->request->variable('device_name', '');

		$user_array = [
			'mobile_browser' 	=> (string) $mobile_browser,
			'device_name' 		=> (string) $device_name
		];

		$sql = 'UPDATE ' . USERS_TABLE . '
			SET ' . $this->db->sql_build_array('UPDATE', $user_array) . '
			WHERE user_id = ' . (int) $this->user->data['user_id'];
		$this->db->sql_query($sql);
	}

	public function page_header($event)
	{
		$page = $this->functions->page_name();

		if ($this->config['mobile_logs_enable'])
		{
			$sql = 'SELECT COUNT(log_ip) AS mobilelogs_counter
				FROM ' . $this->mobilelogs_table . '
				WHERE log_ip = \'' . $this->db->sql_escape($this->user->ip) . "'";
			$result = $this->db->sql_query($sql);
			$mobilelogs_counter = (int) $this->db->sql_fetchfield('mobilelogs_counter');
			$this->db->sql_freeresult($result);

			$status = $this->functions->mobile_device_detect();
			$user_agent = $this->request->server('HTTP_USER_AGENT');
			$user_ip = $this->user->ip;
			$user_name = $this->user->data['username'];
			$device_name = $this->request->variable('device_name', $status[1]);

			if ($mobilelogs_counter == 0 && $device_name != '')
			{
				$sql_ary = [
					'user_agent'	=> (string) $user_agent,
					'log_ip'		=> (string) $user_ip,
					'user_name'		=> (string) $user_name,
					'device_name' 	=> (string) $device_name,
					'log_time'		=> (string) time(),
				];
				$this->db->sql_query('INSERT INTO ' . $this->mobilelogs_table . ' ' . $this->db->sql_build_array('INSERT', $sql_ary));
			}
			else if ($this->user->data['is_mobile'])
			{
				$sql_ary = [
					'user_agent'	=> (string) $user_agent,
					'user_name'		=> (string) $user_name,
					'device_name' 	=> (string) $device_name,
					'log_time'		=> (string) time(),
				];

				$sql = 'UPDATE ' . $this->mobilelogs_table . ' SET ' . $this->db->sql_build_array('UPDATE', $sql_ary) . '
					WHERE ' . $this->db->sql_in_set('log_ip', $this->user->ip);
				$this->db->sql_query($sql);
			}
		}

		$this->template->assign_vars([
			'S_IS_MOBILE'				 	=> $this->user->data['mobile_browser'],
			'S_DEVICE_NAME'					=> $this->user->data['device_name'],
			'MOBILE_NAME'					=> $this->cookie_data['mobile_name'],
			'S_MOBILE_WELCOME'				=> $this->user->data['user_mobile_welcome'],
			'S_MOBILE_HEADER'			 	=> $this->user->data['user_mobile_header'],
			'S_MOBILE_SELF'					=> $this->user->data['user_mobile_self'],
			'U_MOBILE_LOGS'					=> $this->helper->route('sniper_mobiledevice_controller', ['mode' => 'logs']),
			'U_MOBILE_VIEW_LOGS'			=> $this->auth->acl_get('u_mobile_logs_view') ? true : false,
			'U_MOBILE_CLEAR_LOGS'		 	=> $this->auth->acl_get('u_mobile_logs_clear') ? true : false,
			'S_MOBILE_ENABLE'				=> $this->config['mobile_enable'],
			'S_MOBILE_WELCOME_ENABLE'		=> $this->config['mobile_welcome_enable'],
			'S_MOBILE_WELCOME_GUEST_ENABLE' => $this->config['mobile_welcome_guest_enable'],
			'S_MOBILE_HEADER_ENABLE'		=> $this->config['mobile_header_enable'],
			'MOBILE_PROFILE_ENABLE'			=> $this->config['mobile_profile_enable'],
			'MOBILE_LOGS_ENABLE'			=> $this->config['mobile_logs_enable'],
			'MOBILEDEVICE_VERSION'			=> $this->config['mobiledevice_version'],
			'MOBILE_LOGS_REFRESH'		 	=> $this->config['mobile_logs_refresh'],
		]);

		if ($page == 'index' && $this->config['mobile_enable'])
		{
			$this->functions->assign_authors();
			$this->template->assign_var('MOBILEDEVICE_FOOTER_VIEW', true);
		}
	}
}
