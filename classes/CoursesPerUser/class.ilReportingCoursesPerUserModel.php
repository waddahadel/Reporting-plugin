<?php

/**
 * Class ilReportingCoursesPerUserModel
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
class ilReportingCoursesPerUserModel extends ilReportingModel {

	public function __construct() {
		parent::__construct();
		$this->pl = ilReportingPlugin::getInstance();
	}


	/**
	 * Search users
	 *
	 * @param array $filters
	 *
	 * @return array
	 */
	public function getSearchData(array $filters) {

		ilObjOrgUnitTree::_getInstance()->buildTempTableWithUsrAssignements();

		$sql = "SELECT usr_data.usr_id AS id, usr_data.*, (SELECT GROUP_CONCAT(orgu_as.path SEPARATOR ', ') from orgu_usr_assignements AS orgu_as WHERE orgu_as.user_id = usr_data.usr_id) AS org_units 
                 FROM usr_data                 
                 WHERE usr_data.usr_id > 0 AND usr_data.login <> 'anonymous'";

		if ($filters['firstname']) {
			$sql .= ' AND usr_data.firstname LIKE ' . $this->db->quote('%' . str_replace('*', '%', $filters['firstname']) . '%', 'text');
		}
		if ($filters['lastname']) {
			$sql .= ' AND usr_data.lastname LIKE ' . $this->db->quote('%' . str_replace('*', '%', $filters['lastname']) . '%', 'text');
		}
		if ($filters['email']) {
			$sql .= ' AND usr_data.email LIKE ' . $this->db->quote('%' . str_replace('*', '%', $filters['email']) . '%', 'text');
		}
		if ($filters['country']) {
			$sql .= ' AND usr_data.country LIKE ' . $this->db->quote('%' . str_replace('*', '%', $filters['country']) . '%', 'text');
		}
		$sql .= ($filters['include_inactive']) ? ' AND usr_data.active IN(1,0)' : ' AND usr_data.active = 1';

		if (ilReportingConfig::getValue('restricted_user_access') == ilReportingConfig::RESTRICTED_BY_LOCAL_READABILITY) {
			$refIds = $this->getRefIdsWhereUserCanAdministrateUsers();
			if (count($refIds)) {
				$sql .= ' AND usr_data.time_limit_owner IN (' . implode(',', $refIds) . ')';
			} else {
				$sql .= ' AND usr_data.time_limit_owner IN (0)';
			}
		} elseif (ilReportingConfig::getValue('restricted_user_access') == ilReportingConfig::RESTRICTED_BY_ORG_UNITS) {
			//TODO: check if this is performant enough.
			$users = $this->pl->getRestrictedByOrgUnitsUsers();
			$sql .= count($users) ? ' AND usr_data.usr_id IN(' . implode(',', $users) . ')' : ' AND FALSE';
		}

		return $this->buildRecords($sql);
	}


	public function getReportData(array $ids, array $filters) {
		ilObjOrgUnitTree::_getInstance()->buildTempTableWithUsrAssignements();

		$sql = "SELECT usr.usr_id AS id, obj.obj_id, obj.title, CONCAT_WS(' > ', gp.title, p.title) AS path,
	             usr.firstname, usr.lastname, usr.active, usr.country, usr.department, ut.status_changed, ut.status AS user_status, (SELECT GROUP_CONCAT(orgu_as.path SEPARATOR ', ') from orgu_usr_assignements AS orgu_as WHERE orgu_as.user_id = usr.usr_id) AS org_units 
	             FROM object_data as obj
	             INNER JOIN object_reference AS ref ON (ref.obj_id = obj.obj_id)
	             INNER JOIN object_data AS crs_member_role ON crs_member_role.title LIKE CONCAT('il_crs_member_', ref.ref_id)
				 INNER JOIN rbac_ua ON rbac_ua.rol_id = crs_member_role.obj_id
				 INNER JOIN usr_data AS usr ON (usr.usr_id = rbac_ua.usr_id)
				 INNER JOIN tree AS t1 ON (ref.ref_id = t1.child)
				 INNER JOIN object_reference ref2 ON (ref2.ref_id = t1.parent)
				 INNER JOIN object_data AS p ON (ref2.obj_id = p.obj_id)
				 LEFT JOIN tree AS t2 ON (ref2.ref_id = t2.child)
				 LEFT JOIN object_reference AS ref3 ON (ref3.ref_id = t2.parent)
				 LEFT JOIN object_data AS gp ON (ref3.obj_id = gp.obj_id)
				 LEFT JOIN ut_lp_marks AS ut ON (ut.obj_id = obj.obj_id AND ut.usr_id = usr.usr_id)
                 WHERE obj.type = " . $this->db->quote('crs', 'text') . " AND ref.deleted IS NULL ";
		if (count($ids)) {
			$sql .= " AND usr.usr_id IN (" . implode(',', $ids) . ")";
		}
		if (ilReportingConfig::getValue('restricted_user_access') == ilReportingConfig::RESTRICTED_BY_LOCAL_READABILITY) {
			$refIds = $this->getRefIdsWhereUserCanAdministrateUsers();
			if (count($refIds)) {
				$sql .= ' AND usr.time_limit_owner IN (' . implode(',', $refIds) . ')';
			} else {
				$sql .= ' AND usr.time_limit_owner IN (0)';
			}
		} elseif (ilReportingConfig::getValue('restricted_user_access') == ilReportingConfig::RESTRICTED_BY_ORG_UNITS) {
			//TODO: check if this is performant enough.
			$users = $this->pl->getRestrictedByOrgUnitsUsers();
			$sql .= count($users) ? ' AND usr.usr_id IN(' . implode(',', $users) . ') ' : ' AND FALSE ';
		}
		if (count($filters)) {
			if ($filters['status'] != '') {
				$sql .= ' AND ut.status = ' . $this->db->quote(($filters['status'] - 1), 'text');
			}
			if ($date = $filters['status_changed_from']) {
				$sql .= ' AND ut.status_changed >= ' . $this->db->quote($date, 'date');
			}
			if ($date = $filters['status_changed_to']) {
				/** @var $date ilDateTime */
				$date->increment(ilDateTime::DAY, 1);
				$sql .= ' AND ut.status_changed <= ' . $this->db->quote($date, 'date');
				$date->increment(ilDateTime::DAY, - 1);
			}
		}
		$sql .= " ORDER BY usr.usr_id, usr.lastname, usr.firstname";

		$data = $this->buildRecords($sql);

		foreach ($data as &$v) {
			$this->getLPMark($v["obj_id"], $v["id"], $v);
		}

		return $data;
	}
}
