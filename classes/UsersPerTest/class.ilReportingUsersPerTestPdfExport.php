<?php

/**
 * Class ilReportingUsersPerTestPdfExport
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
class ilReportingUsersPerTestPdfExport extends ilReportingPdfExport {

	public function __construct() {
		parent::__construct();
		$this->report_title = $this->pl->txt('users_per_test');
		$this->template_filename = 'users_per_test.jrxml';
		$this->output_filename = 'users_per_test_' . $this->formatter->formatCurrentDate(ilReportingFormatter::EXPORT_FILE_DATE_FORMAT);
	}
}
