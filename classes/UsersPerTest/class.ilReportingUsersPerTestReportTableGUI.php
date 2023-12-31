<?php

/**
 * Report table for the report UsersPerTest
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 */
class ilReportingUsersPerTestReportTableGUI extends ilReportingReportTableGUI {

	public function __construct($a_parent_obj, $a_parent_cmd) {
		parent::__construct($a_parent_obj, $a_parent_cmd);
	}


	public function exportDataCustom($format, $send = false) {
		switch ($format) {
			case ilReportingGUI::EXPORT_PDF:
				$export = new ilReportingUsersPerTestPdfExport();
				$export->setCsvData($this->getData());
				$export->setReportGroups(array( 'id' => 'group_test' ));
				$export->setCsvColumns($this->getColumns());
				$export->execute();
				break;
		}
	}
}
