<?php

/**
 * Report table for the report CoursesPerUser
 *
 * @author            Stefan Wanzenried <sw@studer-raimann.ch>
 * @version           $Id:
 */
class ilReportingCoursesPerUserReportTableGUI extends ilReportingReportTableGUI {

	public function __construct($a_parent_obj, $a_parent_cmd) {
		parent::__construct($a_parent_obj, $a_parent_cmd);
		$this->addCommandButton(ilReportingCoursesPerUserGUI::CMD_SHOW_OBJECTS_IN_COURSE, $this->pl->txt("show_objects_in_course"));
	}


	public function exportDataCustom($format, $send = false) {
		switch ($format) {
			case ilReportingGUI::EXPORT_PDF:
				$export = new ilReportingCoursesPerUserPdfExport();
				$export->setCsvColumns($this->getColumns());
				$export->setReportGroups(array( 'id' => 'group_user' ));
				$export->setCsvData($this->getData());
				$export->execute();
				break;
		}
	}
}
