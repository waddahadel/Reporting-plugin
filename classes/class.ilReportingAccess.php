<?php

require_once __DIR__ . "/../vendor/autoload.php";

class ilReportingAccess {

	/**
	 * @var ilReportingPlugin
	 */
	protected $pl;
	/**
	 * @var ilObjUser
	 */
	protected $usr;
	/**
	 * @var ilRbacReview
	 */
	protected $rbacreview;


	public function __construct() {
		global $DIC;
		$this->usr = $DIC->user();
		$this->rbacreview = $DIC->rbac()->review();
		$this->pl = ilReportingPlugin::getInstance();
	}


	public function hasCurrentUserReportsPermission() {
		//Reporting Access to Employees?
		$arr_orgus_perm_empl = ilObjOrgUnitTree::_getInstance()->getOrgusWhereUserHasPermissionForOperation('view_learning_progress');
		if (count($arr_orgus_perm_empl) > 0) {
			return true;
		}

		//Reporting Access Rec?
		$arr_orgus_perm_sup = ilObjOrgUnitTree::_getInstance()->getOrgusWhereUserHasPermissionForOperation('view_learning_progress_rec');
		if (count($arr_orgus_perm_sup) > 0) {
			return true;
		}

		$global_roles = $this->rbacreview->assignedGlobalRoles($this->usr->getId());
		//Administrator
		if (in_array(2, $global_roles)) {
			return true;
		}

		return false;
	}
}
