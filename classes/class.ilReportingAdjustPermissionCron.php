<?php
require_once "./Modules/OrgUnit/classes/class.ilObjOrgUnit.php";

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


	function __construct() {
		$this->initILIAS();
		global $ilDB, $ilUser, $ilCtrl, $tree;
		/**
		 * @var $ilDB   ilDB
		 * @var $ilUser ilObjUser
		 * @var $ilCtrl ilCtrl
		 * @var $tree   ilTree
		 */
		$this->db = $ilDB;
		$this->user = $ilUser;
		$this->ctrl = $ilCtrl;
		$this->tree = $tree;



		require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Reporting/classes/class.ilReportingPlugin.php');
		$this->pl = new ilReportingPlugin();
	}


	public function initILIAS() {
		chdir(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/Customizing')));

		require_once './Services/Cron/classes/class.ilCronStartUp.php';
		$ilCronStartup = new ilCronStartUp($_SERVER['argv'][3], $_SERVER['argv'][1], $_SERVER['argv'][2]);
		$ilCronStartup->initIlias();
		$ilCronStartup->authenticate();
	}


	public function run() {
		global $ilDB, $ilLog, $rbacadmin;

		//Adjust Permissions Course Member / Admin Roles
		$query = "SELECT obj_id FROM object_data WHERE type = 'rolt' AND title = 'il_orgu_employee'";
		$res = $this->db->query($query);
		while($row = $res->fetchRow(ilDBConstants::FETCHMODE_OBJECT))
		{
			$role_template = $row->obj_id;
		}


		$query = "select ref.ref_id from object_data inner join object_reference as ref on ref.obj_id = object_data.obj_id where type = 'orgu' and owner <> -1";

		$i = 0;
		$res = $ilDB->query($query);

		while ($row = $ilDB->fetchAssoc($res)) {

			$i = $i + 1;

			$ilLog->write('srag - perm orgu - off START counter ' . $i . ' ref_id ' . $row['ref_id']);

			$il_orgu = new ilObjOrgUnit($row['ref_id']);
			$rbacadmin->copyRoleTemplatePermissions($role_template ,ROLE_FOLDER_ID , $row['ref_id'], $il_orgu->getEmployeeRole());
			$role_obj = new ilObjRole($il_orgu->getEmployeeRole());
			$role_obj->changeExistingObjects(
				$row['ref_id'],
				ilObjRole::MODE_UNPROTECTED_KEEP_LOCAL_POLICIES,
				array('all'),
				array());

			$ilLog->write('srag - perm crs - off END counter ' . $i . ' ref_id ' . $row['ref_id']);
		}



	}

}
?>