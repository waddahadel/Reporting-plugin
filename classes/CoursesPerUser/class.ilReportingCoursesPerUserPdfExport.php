<?php

/**
 * Class ilReportingCoursesPerUserPdfExport.
 * PDF Export Courses per User
 *
 * @author  Stefan Wanzenried <sw@studer-raimann.ch>
 * @version $Id:
 *
 */
class ilReportingCoursesPerUserPdfExport extends ilReportingPdfExport {

	public function __construct() {
		parent::__construct();
		$this->report_title = $this->pl->txt('courses_per_user');
		$this->template_filename = 'courses_per_user.jrxml';
		$this->output_filename = 'courses_per_user_' . $this->formatter->formatCurrentDate(ilReportingFormatter::EXPORT_FILE_DATE_FORMAT);
	}
}
