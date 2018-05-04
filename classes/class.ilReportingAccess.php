<?php
require_once './Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/Reporting/classes/class.ilReportingPlugin.php';
require_once './Modules/OrgUnit/classes/class.ilObjOrgUnitTree.php';

class ilReportingAccess {

	public function __construct() {
		$this->pl = new ilReportingPlugin();
	}

	public function hasCurrentUserReportsPermission() {
		global $ilUser, $rbacreview;

		//Reporting Access to Employees?
		$arr_orgus_perm_empl = ilObjOrgUnitTree::_getInstance()->getOrgusWhereUserHasPermissionForOperation('view_learning_progress');
		if(count($arr_orgus_perm_empl) > 0) {
			return true;
		}

		//Reporting Access Rec?
		$arr_orgus_perm_sup = ilObjOrgUnitTree::_getInstance()->getOrgusWhereUserHasPermissionForOperation('view_learning_progress_rec');
		if(count($arr_orgus_perm_sup) > 0) {
			return true;
		}

		$global_roles = $rbacreview->assignedGlobalRoles($ilUser->getId());
		//Administrator
		if(in_array(2,$global_roles)) {
			return true;
		}


		return false;
	}
}