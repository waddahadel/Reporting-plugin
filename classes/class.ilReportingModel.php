<?php

/**
 * Class ilReportingModel: Each report must implement its own model which extends this class.
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
abstract class ilReportingModel {

	/** @var ilDB */
	protected $db;
	/** @var ilReportingPlugin */
	protected $pl;
	/** @var ilObjUser */
	protected $user;
	/** @var ilAccess */
	protected $access;


	public function __construct() {
		global $DIC;
		$this->db = $DIC->database();
		$this->pl = ilReportingPlugin::getInstance();
		$this->user = $DIC->user();
		$this->access = $DIC->access();
	}


	abstract public function getSearchData(array $filters);


	abstract public function getReportData(array $ids, array $filters);


	/**
	 * Return all the ref-ids (of Categories) where the current user can administrate users
	 *
	 * @return array
	 */
	protected function getRefIdsWhereUserCanAdministrateUsers() {
		$sql = 'SELECT DISTINCT time_limit_owner FROM usr_data';
		$set = $this->db->query($sql);
		$refIds = array();
		while ($rec = $this->db->fetchAssoc($set)) {
			$refIds[] = $rec['time_limit_owner'];
		}
		foreach ($refIds as $k => $refId) {
			if (!$this->access->checkAccess('read_users', '', $refId)) {
				unset($refIds[$k]);
			}
		}

		return $refIds;
	}


	/**
	 * Build records from SQL Query string
	 *
	 * @param string $sql SQL String
	 *
	 * @return array
	 */
	protected function buildRecords($sql) {
		$sql = preg_replace('/[ ]{2,}|[\t]|[\n]/', ' ', trim($sql));
		$result = $this->db->query($sql);
		$return = array();
		while ($rec = $this->db->fetchAssoc($result)) {
			$return[] = $rec;
		}

		return $return;
	}


	/**
	 * @param int  $obj_id
	 * @param int  $usr_id
	 * @param array $
	 * @param bool $for_sub_objects - flag if it should say object_grade or just grade
	 */
	protected function getLPMark($obj_id, $usr_id, array &$v, $for_sub_objects = false) {
		$result = $this->db->queryF("SELECT mark,u_comment FROM ut_lp_marks WHERE obj_id=%s AND usr_id=%s", [ "integer", "integer" ], [
			$obj_id,
			$usr_id
		])->fetchAssoc();

		$prefix = $for_sub_objects ? 'object_' : '';

		if ($result) {
			$v[$prefix . "grade"] = $result["mark"];
			$v[$prefix . "comments"] = $result["u_comment"];
		} else {
			$v[$prefix . "grade"] = "";
			$v[$prefix . "comments"] = "";
		}
	}


	/**
	 * @param string $table_name
	 */
	protected function buildTempTableWithUserAssignments($table_name) {
		$q = "DROP TABLE IF EXISTS $table_name";
		$this->db->manipulate($q);

		$q = "CREATE TEMPORARY TABLE IF NOT EXISTS $table_name AS (
				SELECT DISTINCT object_reference.ref_id AS ref_id, rbac_ua.usr_id AS user_id, orgu_path_storage.path AS path
					FROM rbac_ua
					JOIN  rbac_fa ON rbac_fa.rol_id = rbac_ua.rol_id
					JOIN object_reference ON rbac_fa.parent = object_reference.ref_id
					JOIN object_data ON object_data.obj_id = object_reference.obj_id
					JOIN orgu_path_storage ON orgu_path_storage.ref_id = object_reference.ref_id
				WHERE object_data.type = 'orgu' AND object_reference.deleted IS NULL
			);";
		$this->db->manipulate($q);
	}
}
