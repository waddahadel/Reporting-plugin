<?php

/**
 * Class ilReportingUsersPerCoursePdfExport
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
class ilReportingUsersPerCoursePdfExport extends ilReportingPdfExport {

	public function __construct() {
		parent::__construct();
		$this->report_title = $this->pl->txt('users_per_course');
		$this->template_filename = 'users_per_course.jrxml';
		$this->output_filename = 'users_per_course' . date('Y-m-d');
	}
}