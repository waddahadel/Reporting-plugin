<?php

$async = new ilReportingAdjustPermissionCron($_SERVER['argv']);
$async->run();

/**
 * srEducationManagementAdjustPermissionCron
 *
 * @author  Martin Studer <ms@studer-raimann.ch>
 * @version
 */
class ilReportingAdjustPermissionCron {

	/**
	 * @var array
	 */
	protected $crs = array();
	/**
	 * @var ilDB
	 */
	protected $db;
	/**
	 * @var ilObjUser
	 */
	protected $user;
	/**
	 * @var ilCtrl
	 */
	protected $ctrl;
	/**
	 * @var ilTree
	 */
	protected $tree;
	/**
	 * @var ilLog
	 */
	protected $log;
	/**
	 * @var ilRbacAdmin
	 */
	protected $rbacadmin;


	function __construct() {
		$this->initILIAS();
		global $DIC;
		$this->db = $DIC->database();
		$this->user = $DIC->user();
		$this->ctrl = $DIC->ctrl();
		$this->tree = $DIC->repositoryTree();
		$this->log = $DIC["ilLog"];
		$this->rbacadmin = $DIC->rbac()->admin();

		require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Reporting/vendor/autoload.php');
		$this->pl = ilReportingPlugin::getInstance();
	}


	public function initILIAS() {
		chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));

		require_once './Services/Cron/classes/class.ilCronStartUp.php';
		$ilCronStartup = new ilCronStartUp($_SERVER['argv'][3], $_SERVER['argv'][1], $_SERVER['argv'][2]);
		$ilCronStartup->initIlias();
		$ilCronStartup->authenticate();
	}


	public function run() {
		//Adjust Permissions Course Member / Admin Roles
		$query = "SELECT obj_id FROM object_data WHERE type = 'rolt' AND title = 'il_orgu_employee'";
		$res = $this->db->query($query);
		while ($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT)) {
			$role_template = $row->obj_id;
		}

		$query = "select ref.ref_id from object_data inner join object_reference as ref on ref.obj_id = object_data.obj_id where type = 'orgu' and owner <> -1";

		$i = 0;
		$res = $this->db->query($query);

		while ($row = $this->db->fetchAssoc($res)) {

			$i = $i + 1;

			$this->log->write('srag - perm orgu - off START counter ' . $i . ' ref_id ' . $row['ref_id']);

			$il_orgu = new ilObjOrgUnit($row['ref_id']);
			$this->rbacadmin->copyRoleTemplatePermissions($role_template, ROLE_FOLDER_ID, $row['ref_id'], $il_orgu->getEmployeeRole());
			$role_obj = new ilObjRole($il_orgu->getEmployeeRole());
			$role_obj->changeExistingObjects($row['ref_id'], ilObjRole::MODE_UNPROTECTED_KEEP_LOCAL_POLICIES, array( 'all' ), array());

			$this->log->write('srag - perm crs - off END counter ' . $i . ' ref_id ' . $row['ref_id']);
		}
	}
}
