<?php
/**
*
* @package phpBB Extension - Mobile Device
* @copyright (c) 2015-2019 Sniper_E - http://www.sniper-e.com & dmzx - https://www.dmzx-web.net & martin - http://www.martins-phpbb.com
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sniper\mobiledevice\controller;

use phpbb\auth\auth;
use phpbb\config\config;
use phpbb\controller\helper;
use phpbb\db\driver\driver_interface;
use phpbb\pagination;
use phpbb\request\request;
use phpbb\template\template;
use phpbb\user;
use sniper\mobiledevice\core\functions;

class mobilelogs
{
	/** @var functions */
	protected $functions;

	/** @var template */
	protected $template;

	/** @var user */
	protected $user;

	/** @var auth */
	protected $auth;

	/** @var driver_interface */
	protected $db;

	/** @var request */
	protected $request;

	/** @var config */
	protected $config;

	/** @var helper */
	protected $helper;

	/** @var pagination */
	protected $pagination;

	/** @var string database tables */
	protected $mobilelogs_table;

	/**
	* Constructor
	* @param functions 			$functions
	* @param template			$template
	* @param user				$user
	* @param auth				$auth
	* @param driver_interface	$db
	* @param request			$request
	* @param config				$config
	* @param helper				$helper
	* @param pagination			$pagination
	* @param string				$mobilelogs_table
	*/
	public function __construct(
		functions $functions,
		template $template,
		user $user,
		auth $auth,
		driver_interface $db,
		request $request,
		config $config,
		helper $helper,
		pagination $pagination,
		$mobilelogs_table
	)
	{
		$this->functions		= $functions;
		$this->template		    = $template;
		$this->user			    = $user;
		$this->auth			    = $auth;
		$this->db				= $db;
		$this->request			= $request;
		$this->config			= $config;
		$this->helper			= $helper;
		$this->pagination		= $pagination;
		$this->mobilelogs_table = $mobilelogs_table;
	}

	public function handle_mobilelogs()
	{
		$mobile_logs		= ($this->auth->acl_get('u_mobile_logs_view')) ? true : false;
		$mobile_clear_logs = ($this->auth->acl_get('u_mobile_logs_clear')) ? true : false;
		$mobile_view		= ($this->auth->acl_get('u_mobile_logs_view')) ? true : false;
		$mobile_mode		= $this->request->variable('mode', '');
		$log_id			    = $this->request->variable('logid', 0);
		$mobile_logs_page	= $this->helper->route('sniper_mobiledevice_controller', array('mode' => 'logs'));

		$this->functions->assign_authors();
		$this->template->assign_var('MOBILEDEVICE_FOOTER_VIEW', true);

		switch ($mobile_mode)
		{
			case 'logs':

				// must have auths
				if (!$mobile_view || !$mobile_logs)
				{
					trigger_error('NOT_AUTHORISED', E_USER_NOTICE);
				}

				$start = $this->request->variable('start', 0);

				$page_title = $this->user->lang['MOBILE_LOGS'];
				$board_url = generate_board_url() . '/';

				$sql = 'SELECT *
					FROM ' . $this->mobilelogs_table . '
					ORDER BY log_time DESC';
				$result = $this->db->sql_query_limit($sql, $this->config['posts_per_page'], $start);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$this->template->assign_block_vars('logs', array(
						'DEVICE_IMAGE' => '<img src="' . $board_url . 'ext/sniper/mobiledevice/images/' . $row['device_name'] . '.png" alt="?" class="mobile-responsive" />',
						'LOG_ID'		=> $row['log_id'],
						'LOG_TIME'	 => $this->user->format_date($row['log_time']),
						'DEVICE_NAME'	=> $row['device_name'],
						'USER_NAME'	=> $row['user_name'],
						'USER_AGENT'	=> $row['user_agent'],
						'LOG_IP'		=> $row['log_ip'],
					));
				}
				$this->db->sql_freeresult($result);

				$sql = 'SELECT COUNT(log_id) AS log_count
					FROM ' . $this->mobilelogs_table;
				$result = $this->db->sql_query($sql);
				$log_count = (int) $this->db->sql_fetchfield('log_count');
				$this->db->sql_freeresult($result);

				$pagination_url = $this->helper->route('sniper_mobiledevice_controller', array('mode' => 'logs'));

				$this->pagination->generate_template_pagination($pagination_url, 'pagination', 'start', $log_count, (int) $this->config['posts_per_page'], $start);

				$sql = 'SELECT *
					FROM ' . $this->mobilelogs_table;
				$result = $this->db->sql_query($sql);

				while ($row = $this->db->sql_fetchrow($result))
				{
					$this->template->assign_vars(array(
						'U_REMOVE_LOG'		=> $this->helper->route('sniper_mobiledevice_controller', array('mode' => 'remove_log', 'logid' => (int) $row['log_id'])),
						'U_CLEAR_LOGS'		=> $this->helper->route('sniper_mobiledevice_controller', array('mode' => 'clear_logs')),
						'U_MOBILE_VIEW_LOGS'	=> $mobile_view,
						'U_MOBILE_CLEAR_LOGS' => $mobile_clear_logs,
						'COUNT'				=> $log_count,
					));
				}
				$this->db->sql_freeresult($result);

				$this->template->assign_block_vars('navlinks', array(
					'FORUM_NAME'	=> $page_title,
					'U_VIEW_FORUM' => $this->helper->route('sniper_mobiledevice_controller', array('mode' => 'logs')),
				));

				page_header($page_title);

				$this->template->set_filenames(array(
					'body' => 'logs_body.html',
				));

				page_footer();

			break;

			case 'remove_log':

				// Run remove!
				$sql = 'DELETE FROM ' . $this->mobilelogs_table . '
					WHERE log_id = ' . (int) $log_id;
				$this->db->sql_query($sql);

				meta_refresh(0, $mobile_logs_page);
				trigger_error($this->user->lang['MOBILE_LOG_DELETED']);

			break;

			case 'clear_logs':

				if (confirm_box(true))
				{
					// Run delete!
					$sql = 'DELETE FROM ' . $this->mobilelogs_table;
					$this->db->sql_query($sql);

					meta_refresh(3, $mobile_logs_page);
					trigger_error($this->user->lang['MOBILE_LOGS_CLEANED']. '<br><br>' . sprintf($this->user->lang['RETURN_PAGE'], '<a href="' . $mobile_logs_page . '">', '</a>'));
				}
				else
				{
					// Display confirm box
					confirm_box(false, $this->user->lang['MOBILE_LOGS_CLEAR']);
				}
				redirect($mobile_logs_page);

			break;
		}
	}
}
